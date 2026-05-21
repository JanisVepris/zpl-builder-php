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

    /** Create a bare builder. Prefer the `start()` factory for the typical flow. */
    public function __construct()
    {
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
    }

    /** Render the accumulated commands as a ZPL string (alias for `render()`). */
    public function __toString(): string
    {
        return $this->render();
    }

    /** Open a new ZPL format. Returns a builder with `^XA` already appended. */
    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
    }

    /**
     * Register a named font preset that can later be applied via `applyFontPreset()`.
     * Unspecified dimensions inherit from the font's current settings.
     */
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

    /** Drop a previously registered font preset. No-op if the name isn't registered. */
    public function removeFontPreset(string $name): self
    {
        unset($this->fontPresets[$name]);

        return $this;
    }

    /** Whether a font preset with the given name has been registered. */
    public function hasFontPreset(string $name): bool
    {
        return isset($this->fontPresets[$name]);
    }

    /**
     * Return all currently registered font presets, keyed by name.
     *
     * @return array<string, FontPreset>
     */
    public function getFontPresets(): array
    {
        return $this->fontPresets;
    }

    /** Apply a previously registered font preset, emitting `^CF` with its stored dimensions. */
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

    /**
     * Change the default font (`^CF`) and optionally its height and/or width.
     * Unspecified dimensions keep the last value set for that font.
     */
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

    /**
     * Render the accumulated commands as a ZPL string.
     * Pure — does not finalise the format. Call `end()` first if you want `^XZ`.
     */
    public function render(): string
    {
        if ($this->commands === []) {
            return '';
        }

        $separator = $this->printNewlines ? PHP_EOL : '';

        return implode($separator, array_map('strval', $this->commands)).$separator;
    }

    /**
     * Return the list of commands accumulated so far. Useful for testing and external rendering.
     *
     * @return Commands[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /** Finalise the format by appending `^XZ`. */
    public function end(): self
    {
        return $this->addCommand(new Commands\EndFormat());
    }

    /** Toggle whether `render()` separates each ZPL command with a newline. Off by default. */
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

    /** Flip the label between normal and inverted (`^PO`). */
    public function printOrientation(LabelFlip $orientation): self
    {
        return $this->addCommand(new Commands\PrintOrientation($orientation));
    }

    /** Set how many labels to print (`^PQ`). */
    public function printQuantity(int $quantity): self
    {
        return $this->addCommand(new Commands\PrintQuantity($quantity));
    }

    /**
     * Set defaults for subsequent barcodes — module width, wide-to-narrow ratio,
     * and bar height (`^BY`).
     */
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

    /**
     * Draw a Code 128 barcode with the given data (`^BC` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided.
     */
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

    /**
     * Write text into the current field (`^FD ... ^FS`). Auto-escapes `^` and `~`
     * via `^FH_` if present, since the printer would otherwise treat them as command starts.
     */
    public function fieldData(string $data): self
    {
        if (str_contains($data, '^') || str_contains($data, '~')) {
            $this->fieldHexIndicator();
            $data = FieldDataEncoder::escape($data);
        }

        $this->addCommand(new Commands\FieldData($data));

        return $this->addCommand(new Commands\FieldSeparator());
    }

    /** Set the printer's character encoding (`^CI`), with optional character remaps. */
    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self
    {
        return $this->addCommand(
            new Commands\ChangeInternationalEncoding($encoding, ...$characterRemaps),
        );
    }

    /** Declare the hex-escape character used by the next `^FD` (`^FH`). */
    public function fieldHexIndicator(string $indicator = '_'): self
    {
        return $this->addCommand(new Commands\FieldHexIndicator($indicator));
    }

    /** Tag the next field with a number, for use with stored formats (`^FN`). */
    public function fieldNumber(int $number): self
    {
        return $this->addCommand(new Commands\FieldNumber($number));
    }

    /** Set the rotation applied to subsequent fields (`^FW`). */
    public function fieldOrientation(Orientation $rotation): self
    {
        return $this->addCommand(new Commands\FieldOrientation($rotation));
    }

    /** Position the next field at the given (x, y) coordinate in dots (`^FO`). */
    public function fieldOrigin(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldOrigin($x, $y));
    }

    /**
     * Format the next field as a multi-line text block with the given width,
     * maximum line count, line spacing, justification, and hanging indent (`^FB`).
     */
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

    /** Toggle reverse-print — fields render white-on-black instead of black-on-white (`^LR`). */
    public function labelReversePrint(bool $reversePrint = true): self
    {
        return $this->addCommand(new Commands\LabelReversePrint($reversePrint));
    }

    /** Set the label's print width in dots (`^PW`). */
    public function printWidth(int $width): self
    {
        return $this->addCommand(new Commands\PrintWidth($width));
    }

    /** Set the label's length in dots (`^LL`). */
    public function labelLength(int $length): self
    {
        return $this->addCommand(new Commands\LabelLength($length));
    }

    /** Move the label's home origin to the given (x, y) coordinate (`^LH`). */
    public function labelHome(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\LabelHome($x, $y));
    }

    /**
     * Draw a rectangle or line of the given width × height with the chosen thickness,
     * color, and corner rounding (`^GB ... ^FS`).
     */
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

    /** Insert a non-printing comment into the ZPL output (`^FX`). Useful for debugging. */
    public function comment(string $text): self
    {
        return $this->addCommand(new Commands\FieldComment($text));
    }

    /** Invoke a stored format from the printer's memory (`^XF`). */
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

    /**
     * Discard all state and re-emit `^XA`. Clears the command list, font settings,
     * presets, barcode defaults, and the newline preference.
     */
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

    /** Lazy-allocate and return the `FontSettings` for the given font. */
    private function fontSettingsFor(Font $font): FontSettings
    {
        return $this->fontSettings[$font->value] ??= new FontSettings();
    }

    /** Append a command to the internal list. All public mutation methods route through this. */
    private function addCommand(Commands $command): self
    {
        $this->commands[] = $command;

        return $this;
    }
}
