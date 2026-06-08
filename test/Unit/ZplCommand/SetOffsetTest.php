<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockSet;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetOffset;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetOffset::class)]
#[UsesClass(ClockSet::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetOffsetTest extends UnitTestCase
{
    public function testDaysOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 32001, 0, 0, 0, 0);
    }

    public function testDaysOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, -32001, 0, 0, 0, 0);
    }

    public function testHoursOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, 32001, 0, 0);
    }

    public function testHoursOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, -32001, 0, 0);
    }

    public function testMinutesOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, 0, 32001, 0);
    }

    public function testMinutesOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, 0, -32001, 0);
    }

    public function testMonthsOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 32001, 0, 0, 0, 0, 0);
    }

    public function testMonthsOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, -32001, 0, 0, 0, 0, 0);
    }

    public function testRendersMaxBoundaryOffsets(): void
    {
        $command = new SetOffset(ClockSet::Tertiary, 32000, 32000, 32000, 32000, 32000, 32000);

        self::assertSame('^SO3,32000,32000,32000,32000,32000,32000', (string) $command);
    }

    public function testRendersMinBoundaryOffsets(): void
    {
        $command = new SetOffset(ClockSet::Secondary, -32000, -32000, -32000, -32000, -32000, -32000);

        self::assertSame('^SO2,-32000,-32000,-32000,-32000,-32000,-32000', (string) $command);
    }

    public function testRendersSecondaryOffset(): void
    {
        $command = new SetOffset(ClockSet::Secondary, 1, 2, 3, 4, 5, 6);

        self::assertSame('^SO2,1,2,3,4,5,6', (string) $command);
    }

    public function testSecondsOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, 0, 0, 32001);
    }

    public function testSecondsOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 0, 0, 0, -32001);
    }

    public function testYearsOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, 32001, 0, 0, 0);
    }

    public function testYearsOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetOffset(ClockSet::Secondary, 0, 0, -32001, 0, 0, 0);
    }
}
