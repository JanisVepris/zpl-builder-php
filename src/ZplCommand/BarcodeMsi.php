<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MsiCheckDigit;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeMsi implements ZplCommand
{
    public const string COMMAND = '^BM';
    public const string FORMAT = '%s,%s,%d,%s,%s,%s';

    private MsiCheckDigit $checkDigit;
    private int $height;
    private bool $insertCheckDigitInInterpretation;
    private bool $interpretationAboveCode;
    private Orientation $orientation;
    private bool $printInterpretation;

    public function __construct(
        Orientation $orientation,
        MsiCheckDigit $checkDigit,
        int $height,
        bool $printInterpretation,
        bool $interpretationAboveCode,
        bool $insertCheckDigitInInterpretation,
    ) {
        ValueAssert::int($height, 1);

        $this->orientation = $orientation;
        $this->checkDigit = $checkDigit;
        $this->height = $height;
        $this->printInterpretation = $printInterpretation;
        $this->interpretationAboveCode = $interpretationAboveCode;
        $this->insertCheckDigitInInterpretation = $insertCheckDigitInInterpretation;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->checkDigit->value,
            $this->height,
            BoolToStr::conv($this->printInterpretation),
            BoolToStr::conv($this->interpretationAboveCode),
            BoolToStr::conv($this->insertCheckDigitInInterpretation),
        );
    }
}
