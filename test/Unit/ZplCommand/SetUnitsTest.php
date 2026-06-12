<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MeasurementUnit;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetUnits;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetUnits::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetUnitsTest extends UnitTestCase
{
    public function testBaseDpiAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUnits(MeasurementUnit::Dots, 301, null);
    }

    public function testBaseDpiBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUnits(MeasurementUnit::Dots, 149, null);
    }

    public function testConversionDpiAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUnits(MeasurementUnit::Dots, 150, 601);
    }

    public function testConversionDpiBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUnits(MeasurementUnit::Dots, 150, 299);
    }

    public function testConversionIgnoredWithoutBase(): void
    {
        $command = new SetUnits(MeasurementUnit::Dots, null, 600);

        self::assertSame('^MUD', (string) $command);
    }

    public function testRendersBaseWithoutConversion(): void
    {
        $command = new SetUnits(MeasurementUnit::Dots, 200, null);

        self::assertSame('^MUD,200', (string) $command);
    }

    public function testRendersConversion(): void
    {
        $command = new SetUnits(MeasurementUnit::Dots, 150, 300);

        self::assertSame('^MUD,150,300', (string) $command);
    }

    public function testRendersUnitsOnly(): void
    {
        $command = new SetUnits(MeasurementUnit::Millimeters, null, null);

        self::assertSame('^MUM', (string) $command);
    }
}
