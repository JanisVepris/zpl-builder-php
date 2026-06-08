<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockSet;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetOffset implements ZplCommand
{
    public const string COMMAND = '^SO';
    public const string FORMAT = '%s,%d,%d,%d,%d,%d,%d';

    /** Upper bound (inclusive) for each offset value. */
    public const int MAX_OFFSET = 32000;

    /** Lower bound (inclusive) for each offset value. */
    public const int MIN_OFFSET = -32000;

    private ClockSet $clockSet;
    private int $daysOffset;
    private int $hoursOffset;
    private int $minutesOffset;
    private int $monthsOffset;
    private int $secondsOffset;
    private int $yearsOffset;

    public function __construct(
        ClockSet $clockSet,
        int $monthsOffset,
        int $daysOffset,
        int $yearsOffset,
        int $hoursOffset,
        int $minutesOffset,
        int $secondsOffset,
    ) {
        ValueAssert::int($monthsOffset, self::MIN_OFFSET, self::MAX_OFFSET);
        ValueAssert::int($daysOffset, self::MIN_OFFSET, self::MAX_OFFSET);
        ValueAssert::int($yearsOffset, self::MIN_OFFSET, self::MAX_OFFSET);
        ValueAssert::int($hoursOffset, self::MIN_OFFSET, self::MAX_OFFSET);
        ValueAssert::int($minutesOffset, self::MIN_OFFSET, self::MAX_OFFSET);
        ValueAssert::int($secondsOffset, self::MIN_OFFSET, self::MAX_OFFSET);

        $this->clockSet = $clockSet;
        $this->monthsOffset = $monthsOffset;
        $this->daysOffset = $daysOffset;
        $this->yearsOffset = $yearsOffset;
        $this->hoursOffset = $hoursOffset;
        $this->minutesOffset = $minutesOffset;
        $this->secondsOffset = $secondsOffset;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->clockSet->value,
            $this->monthsOffset,
            $this->daysOffset,
            $this->yearsOffset,
            $this->hoursOffset,
            $this->minutesOffset,
            $this->secondsOffset,
        );
    }
}
