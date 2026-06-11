<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeCode93 implements ZplCommand
{
    public const string COMMAND = '^BA';
    public const string FORMAT = '%s,%d,%s,%s,%s';

    private int $height;
    private bool $interpretationAboveCode;
    private Orientation $orientation;
    private bool $printCheckDigit;
    private bool $printInterpretation;

    public function __construct(
        Orientation $orientation,
        int $height,
        bool $printInterpretation,
        bool $interpretationAboveCode,
        bool $printCheckDigit,
    ) {
        ValueAssert::int($height, 1);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->printInterpretation = $printInterpretation;
        $this->interpretationAboveCode = $interpretationAboveCode;
        $this->printCheckDigit = $printCheckDigit;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            BoolToStr::conv($this->printInterpretation),
            BoolToStr::conv($this->interpretationAboveCode),
            BoolToStr::conv($this->printCheckDigit),
        );
    }
}
