<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplCommand as Commands;
use Stringable;

class ZplBuilder implements Stringable
{
    /** @var Commands[] */
    private array $commands = [];

    /** @var FontSettings[] */
    private array $fontSettings = [];

    /** @var array<string, FontPreset> */
    private array $fontPresets = [];

    private bool $printNewlines = false;

    private BarcodeDefaultSettings $barcodeDefaultSettings;

    public function __construct()
    {
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
    }

    public function addFontPreset(
        string $name,
        Font $font,
        ?int $height = null,
        ?int $width = null,
    ): self {
        $settings = $this->fontSettingsFor($font);

        $this->fontPresets[$name] = new FontPreset(
            font: $font,
            height: $height ?? $settings->height(),
            width: $width ?? $settings->width(),
        );

        return $this;
    }

    public function removeFontPreset(string $name): self
    {
        unset($this->fontPresets[$name]);

        return $this;
    }

    public function hasFontPreset(string $name): bool
    {
        return isset($this->fontPresets[$name]);
    }

    /** @return array<string, FontPreset> */
    public function getFontPresets(): array
    {
        return $this->fontPresets;
    }

    public function applyFontPreset(string $name): self
    {
        if (!isset($this->fontPresets[$name])) {
            throw new FontPresetDoesNotExistException($name);
        }

        $preset = $this->fontPresets[$name];

        $this->changeFont(
            font: $preset->font,
            height: $preset->height,
            width: $preset->width,
        );

        return $this;
    }

    public function changeFont(Font $font, ?int $height = null, ?int $width = null): self
    {
        $settings = $this->fontSettingsFor($font);

        if ($height !== null) {
            $settings->setHeight($height);
        }

        if ($width !== null) {
            $settings->setWidth($width);
        }

        return $this->addCommand(
            new Commands\ChangeFont($font, $settings->height(), $settings->width()),
        );
    }

    public function render(): string
    {
        if ($this->commands === []) {
            return '';
        }

        $separator = $this->printNewlines ? PHP_EOL : '';

        return implode($separator, array_map('strval', $this->commands)).$separator;
    }

    /** @return Commands[] */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function end(): self
    {
        return $this->addCommand(new Commands\EndFormat());
    }

    /** Print newlines after each ZPL command in the resulting output */
    public function printNewlines(bool $toggle = true): self
    {
        $this->printNewlines = $toggle;

        return $this;
    }

    /**
     * Append a literal ZPL fragment without validation. Use for commands the
     * builder does not yet have a dedicated method for.
     */
    public function raw(string $zpl): self
    {
        return $this->addCommand(new Commands\RawCommand($zpl));
    }

    public function printOrientation(LabelFlip $orientation): self
    {
        return $this->addCommand(new Commands\PrintOrientation($orientation));
    }

    public function printQuantity(int $quantity): self
    {
        return $this->addCommand(new Commands\PrintQuantity($quantity));
    }

    public function barcodeDefaults(
        int $moduleWidth = 2,
        float $wideToNarrowRatio = 3.0,
        int $height = 100,
    ): self {
        $this->barcodeDefaultSettings->setModuleWidth($moduleWidth);
        $this->barcodeDefaultSettings->setWideToNarrowRatio($wideToNarrowRatio);
        $this->barcodeDefaultSettings->setHeight($height);

        return $this->addCommand(
            new Commands\BarcodeDefaults(
                moduleWidth: $this->barcodeDefaultSettings->moduleWidth(),
                wideToNarrowRatio: $this->barcodeDefaultSettings->wideToNarrowRatio(),
                height: $this->barcodeDefaultSettings->height(),
            ),
        );
    }

    public function barcodeCode128(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $useUccCheckDigit = false,
        Code128Mode $mode = Code128Mode::None,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode128(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                useUccCheckDigit: $useUccCheckDigit,
                mode: $mode,
            ),
        );

        return $this->fieldData($data);
    }

    public function fieldData(string $data): self
    {
        if (str_contains($data, '^') || str_contains($data, '~')) {
            $this->fieldHexIndicator();
            $data = FieldDataEncoder::escape($data);
        }

        $this->addCommand(new Commands\FieldData($data));

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self
    {
        return $this->addCommand(
            new Commands\ChangeInternationalEncoding($encoding, ...$characterRemaps),
        );
    }

    public function fieldHexIndicator(string $indicator = '_'): self
    {
        return $this->addCommand(new Commands\FieldHexIndicator($indicator));
    }

    public function fieldNumber(int $number): self
    {
        return $this->addCommand(new Commands\FieldNumber($number));
    }

    public function fieldOrientation(Orientation $rotation): self
    {
        return $this->addCommand(new Commands\FieldOrientation($rotation));
    }

    public function fieldOrigin(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldOrigin($x, $y));
    }

    public function fieldBlock(
        int $width = 0,
        int $maxLines = 1,
        int $lineSpacing = 0,
        Justify $justify = Justify::Left,
        int $hangingIndent = 0,
    ): self {
        return $this->addCommand(
            new Commands\FieldBlock(
                width: $width,
                maxLines: $maxLines,
                lineSpacing: $lineSpacing,
                justify: $justify,
                hangingIndent: $hangingIndent,
            ),
        );
    }

    public function labelReversePrint(bool $reversePrint = true): self
    {
        return $this->addCommand(new Commands\LabelReversePrint($reversePrint));
    }

    public function printWidth(int $width): self
    {
        return $this->addCommand(new Commands\PrintWidth($width));
    }

    public function labelLength(int $length): self
    {
        return $this->addCommand(new Commands\LabelLength($length));
    }

    public function labelHome(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\LabelHome($x, $y));
    }

    public function graphicBox(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
        int $rounding = 0,
    ): self {
        $this->addCommand(
            new Commands\GraphicBox(
                width: $width,
                height: $height,
                thickness: $thickness,
                color: $color,
                rounding: $rounding,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function comment(string $text): self
    {
        return $this->addCommand(new Commands\FieldComment($text));
    }

    public function recallFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self {
        return $this->addCommand(
            new Commands\RecallFormat(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function reset(): self
    {
        $this->commands = [];
        $this->fontSettings = [];
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
        $this->fontPresets = [];
        $this->printNewlines = false;
        $this->addCommand(new Commands\StartFormat());

        return $this;
    }

    private function fontSettingsFor(Font $font): FontSettings
    {
        return $this->fontSettings[$font->value] ??= new FontSettings();
    }

    private function addCommand(Commands $command): self
    {
        $this->commands[] = $command;

        return $this;
    }
}
