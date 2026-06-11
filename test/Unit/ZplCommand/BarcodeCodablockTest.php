<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CodablockMode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCodablock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeCodablock::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(CodablockMode::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeCodablockTest extends UnitTestCase
{
    public function testCharactersPerRowAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, 63, null, CodablockMode::ModeF);
    }

    public function testCharactersPerRowBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, 1, null, CodablockMode::ModeF);
    }

    public function testRendersFullySpecifiedModeA(): void
    {
        $command = new BarcodeCodablock(Orientation::Rotate90, 10, false, 30, 22, CodablockMode::ModeA);

        self::assertSame('^BBR,10,N,30,22,A', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeCodablock(Orientation::Rotate0, 8, true, null, null, CodablockMode::ModeF);

        self::assertSame('^BBN,8,Y,,,F', (string) $command);
    }

    public function testRowHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 1, true, null, null, CodablockMode::ModeF);
    }

    public function testRowsAboveMaxForModeAThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, null, 23, CodablockMode::ModeA);
    }

    public function testRowsAboveMaxForModeFThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, null, 5, CodablockMode::ModeF);
    }

    public function testRowsBelowMinForModeAThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, null, 0, CodablockMode::ModeA);
    }

    public function testRowsBelowMinForModeFThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodablock(Orientation::Rotate0, 8, true, null, 1, CodablockMode::ModeF);
    }
}
