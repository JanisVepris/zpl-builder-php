<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ValueObject\AztecErrorControl;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeAztec implements ZplCommand
{
    public const string COMMAND = '^B0';
    public const string FORMAT = '%s,%d,%s,%d,%s,%d';
    public const string FORMAT_WITH_ID = '%s,%d,%s,%d,%s,%d,%s';
    public const int MAX_ID_BYTES = 24;

    public const int MAX_MAGNIFICATION = 10;
    public const int MAX_SYMBOL_COUNT = 26;

    private AztecErrorControl $errorControl;
    private bool $extendedChannelInterpretation;
    private int $magnification;
    private bool $menuSymbol;
    private Orientation $orientation;
    private string $structuredAppendId;
    private int $symbolCount;

    public function __construct(
        Orientation $orientation,
        int $magnification,
        bool $extendedChannelInterpretation,
        AztecErrorControl $errorControl,
        bool $menuSymbol,
        int $symbolCount,
        string $structuredAppendId,
    ) {
        ValueAssert::int($magnification, 1, self::MAX_MAGNIFICATION);
        ValueAssert::int($symbolCount, 1, self::MAX_SYMBOL_COUNT);
        ValueAssert::stringLengthBytes($structuredAppendId, 0, self::MAX_ID_BYTES);
        ValueAssert::stringNotContains($structuredAppendId, ['^', '~', ',']);

        $this->orientation = $orientation;
        $this->magnification = $magnification;
        $this->extendedChannelInterpretation = $extendedChannelInterpretation;
        $this->errorControl = $errorControl;
        $this->menuSymbol = $menuSymbol;
        $this->symbolCount = $symbolCount;
        $this->structuredAppendId = $structuredAppendId;
    }

    public function __toString()
    {
        if ($this->structuredAppendId === '') {
            return self::COMMAND . sprintf(
                self::FORMAT,
                $this->orientation->value,
                $this->magnification,
                BoolToStr::conv($this->extendedChannelInterpretation),
                $this->errorControl->value(),
                BoolToStr::conv($this->menuSymbol),
                $this->symbolCount,
            );
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_ID,
            $this->orientation->value,
            $this->magnification,
            BoolToStr::conv($this->extendedChannelInterpretation),
            $this->errorControl->value(),
            BoolToStr::conv($this->menuSymbol),
            $this->symbolCount,
            $this->structuredAppendId,
        );
    }
}
