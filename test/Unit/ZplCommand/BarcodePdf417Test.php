<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePdf417;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodePdf417::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodePdf417Test extends UnitTestCase
{
    public function testColumnsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 10, 0, 31, null, false);
    }

    public function testColumnsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 10, 0, 0, null, false);
    }

    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 32001, 0, null, null, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 0, 0, null, null, false);
    }

    public function testRendersFullySpecified(): void
    {
        $command = new BarcodePdf417(Orientation::Rotate0, 8, 5, 7, 21, true);

        self::assertSame('^B7N,8,5,7,21,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodePdf417(Orientation::Rotate0, 10, 0, null, null, false);

        self::assertSame('^B7N,10,0,,,N', (string) $command);
    }

    public function testRendersWithOnlyRowsSpecified(): void
    {
        $command = new BarcodePdf417(Orientation::Rotate90, 5, 5, null, 83, false);

        self::assertSame('^B7R,5,5,,83,N', (string) $command);
    }

    public function testRowsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 10, 0, null, 91, false);
    }

    public function testRowsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 10, 0, null, 2, false);
    }

    public function testSecurityLevelAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePdf417(Orientation::Rotate0, 10, 9, null, null, false);
    }
}
