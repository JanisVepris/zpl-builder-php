<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeUpcA implements ZplCommand
{
    public const string COMMAND = '^BU';
    public const string FORMAT = '%s,%d,%s,%s,%s';

    public const int MAX_HEIGHT = 9999;

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
        ValueAssert::int($height, 1, self::MAX_HEIGHT);

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
