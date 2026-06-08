<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockTimeFormat;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetDateTime implements ZplCommand
{
    public const string COMMAND = '^ST';
    public const string FORMAT = '%02d,%02d,%04d,%02d,%02d,%02d,%s';
    public const int MAX_YEAR = 2097;
    public const int MIN_YEAR = 1998;
    private int $day;
    private ClockTimeFormat $format;
    private int $hour;
    private int $minute;

    private int $month;
    private int $second;
    private int $year;

    public function __construct(
        int $month,
        int $day,
        int $year,
        int $hour,
        int $minute,
        int $second,
        ClockTimeFormat $format,
    ) {
        ValueAssert::int($month, 1, 12);
        ValueAssert::int($day, 1, 31);
        ValueAssert::int($year, self::MIN_YEAR, self::MAX_YEAR);
        ValueAssert::int($hour, 0, 23);
        ValueAssert::int($minute, 0, 59);
        ValueAssert::int($second, 0, 59);

        $this->month = $month;
        $this->day = $day;
        $this->year = $year;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->format = $format;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->month,
            $this->day,
            $this->year,
            $this->hour,
            $this->minute,
            $this->second,
            $this->format->value,
        );
    }
}
