<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeCode128 implements ZplCommand
{
    public const string COMMAND = '^BC';
    public const string FORMAT = '%s,%d,%s,%s,%s,%s';
    private int $height;
    private bool $interpretationAboveCode;
    private Code128Mode $mode;
    private Orientation $orientation;
    private bool $printInterpretation;
    private bool $useUccCheckDigit;

    public function __construct(
        Orientation $orientation,
        int $height,
        bool $printInterpretation,
        bool $interpretationAboveCode,
        bool $useUccCheckDigit,
        Code128Mode $mode,
    ) {
        ValueAssert::int($height, 1);
        $this->interpretationAboveCode = $interpretationAboveCode;
        $this->printInterpretation = $printInterpretation;
        $this->height = $height;
        $this->orientation = $orientation;
        $this->useUccCheckDigit = $useUccCheckDigit;
        $this->mode = $mode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            BoolToStr::conv($this->printInterpretation),
            BoolToStr::conv($this->interpretationAboveCode),
            BoolToStr::conv($this->useUccCheckDigit),
            $this->mode->value,
        );
    }
}
