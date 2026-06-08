<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockTimeFormat;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetDateTime::class)]
#[UsesClass(ClockTimeFormat::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetDateTimeTest extends UnitTestCase
{
    public function testDayAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 32, 2026, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testDayBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 0, 2026, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testHourAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, 24, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testHourBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, -1, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testMinuteAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, 0, 60, 0, ClockTimeFormat::Military24Hour);
    }

    public function testMinuteBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, 0, -1, 0, ClockTimeFormat::Military24Hour);
    }

    public function testMonthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(13, 1, 2026, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testMonthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(0, 1, 2026, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testRendersAmTimeZeroPadded(): void
    {
        $command = new SetDateTime(3, 7, 2026, 9, 5, 1, ClockTimeFormat::Am);

        self::assertSame('^ST03,07,2026,09,05,01,A', (string) $command);
    }

    public function testRendersMaxBoundaries(): void
    {
        $command = new SetDateTime(12, 31, 2097, 23, 59, 59, ClockTimeFormat::Pm);

        self::assertSame('^ST12,31,2097,23,59,59,P', (string) $command);
    }

    public function testRendersMilitaryTime(): void
    {
        $command = new SetDateTime(11, 25, 1998, 14, 30, 0, ClockTimeFormat::Military24Hour);

        self::assertSame('^ST11,25,1998,14,30,00,M', (string) $command);
    }

    public function testSecondAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, 0, 0, 60, ClockTimeFormat::Military24Hour);
    }

    public function testSecondBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2026, 0, 0, -1, ClockTimeFormat::Military24Hour);
    }

    public function testYearAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 2098, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }

    public function testYearBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDateTime(1, 1, 1997, 0, 0, 0, ClockTimeFormat::Military24Hour);
    }
}
