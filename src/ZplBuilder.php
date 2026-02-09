<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\PrintOrientation;
use Janisvepris\ZplBuilder\Exception\CommandAfterEndException;
use Janisvepris\ZplBuilder\ZplCommand as Commands;

class ZplBuilder
{
    /** @var Commands[] */
    private array $commands = [];

    private int $printQuantity = 1;

    private bool $formatEnded = false;

    /** @var FontSettings[] */
    private array $fontSettings = [];

    private BarcodeDefaultSettings $barcodeDefaultSettings;

    public function __construct()
    {
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
        $this->initFontSettings();
    }

    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
    }

    public function printOrientation(PrintOrientation $orientation): self
    {
        return $this->addCommand(new Commands\PrintOrientation($orientation));
    }

    public function printQuantity(int $quantity): self
    {
        $this->printQuantity = $quantity;

        return $this;
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
        Orientation $orientation = Orientation::ROTATE_0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $useUccCheckDigit = false,
        Code128Mode $mode = Code128Mode::No_mode,
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

        return $this->addCommand(new Commands\FieldData($data));
    }

    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self
    {
        return $this->addCommand(
            new Commands\ChangeInternationalEncoding($encoding, ...$characterRemaps),
        );
    }

    public function changeFont(int|string $font, ?int $height, ?int $width): self
    {
        if ($height !== null) {
            $this->fontSettings[$font]->setHeight($height);
        }

        if ($width !== null) {
            $this->fontSettings[$font]->setWidth($width);
        }

        return $this->addCommand(
            new Commands\ChangeFont(
                $font,
                $this->fontSettings[$font]->height(),
                $this->fontSettings[$font]->width(),
            ),
        );
    }

    public function fieldData(string $data): self
    {
        $this->addCommand(new Commands\FieldData($data));

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function fieldHexIndicator(string $indicator = '_'): self
    {
        return $this->addCommand(new Commands\FieldHexIndicator($indicator));
    }

    public function fieldNum(int $number): self
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

    public function render(): string
    {
        if (!$this->formatEnded) {
            $this->end();
        }

        $string = '';

        foreach ($this->commands as $command) {
            $string .= $command->__toString();
        }

        return $string;
    }

    public function end(): self
    {
        if ($this->formatEnded) {
            return $this;
        }

        $this->addCommand(new Commands\PrintQuantity($this->printQuantity));
        $this->addCommand(new Commands\EndFormat());

        $this->formatEnded = true;

        return $this;
    }

    public function reset(): self
    {
        $this->commands = [];
        $this->initFontSettings();
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
        $this->printQuantity = 1;
        $this->formatEnded = false;
        $this->addCommand(new Commands\StartFormat());

        return $this;
    }

    private function initFontSettings(): void
    {
        $settings = [];

        foreach (range('A', 'Z') as $key) {
            $settings[$key] = new FontSettings();
        }

        foreach (range(0, 9) as $key) {
            $settings[$key] = new FontSettings();
        }

        $this->fontSettings = $settings;
    }

    /** @throws CommandAfterEndException */
    private function addCommand(Commands $command): self
    {
        if ($this->formatEnded) {
            throw new CommandAfterEndException();
        }

        $this->commands[] = $command;

        return $this;
    }
}
