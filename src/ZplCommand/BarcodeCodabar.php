<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CodabarCharacter;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeCodabar implements ZplCommand
{
    public const string COMMAND = '^BK';

    /** The `e` (check digit) parameter is fixed to `N` by the spec. */
    public const string FORMAT = '%s,N,%d,%s,%s,%s,%s';

    private int $height;
    private bool $interpretationAboveCode;
    private Orientation $orientation;
    private bool $printInterpretation;
    private CodabarCharacter $startCharacter;
    private CodabarCharacter $stopCharacter;

    public function __construct(
        Orientation $orientation,
        int $height,
        bool $printInterpretation,
        bool $interpretationAboveCode,
        CodabarCharacter $startCharacter,
        CodabarCharacter $stopCharacter,
    ) {
        ValueAssert::int($height, 1);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->printInterpretation = $printInterpretation;
        $this->interpretationAboveCode = $interpretationAboveCode;
        $this->startCharacter = $startCharacter;
        $this->stopCharacter = $stopCharacter;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            BoolToStr::conv($this->printInterpretation),
            BoolToStr::conv($this->interpretationAboveCode),
            $this->startCharacter->value,
            $this->stopCharacter->value,
        );
    }
}
