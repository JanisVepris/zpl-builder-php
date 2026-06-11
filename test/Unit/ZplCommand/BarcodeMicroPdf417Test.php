<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMicroPdf417;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeMicroPdf417::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeMicroPdf417Test extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMicroPdf417(Orientation::Rotate0, 10000, 0);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMicroPdf417(Orientation::Rotate0, 0, 0);
    }

    public function testModeAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMicroPdf417(Orientation::Rotate0, 10, 34);
    }

    public function testModeBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMicroPdf417(Orientation::Rotate0, 10, -1);
    }

    public function testRendersRotatedFullySpecified(): void
    {
        $command = new BarcodeMicroPdf417(Orientation::Rotate90, 9999, 33);

        self::assertSame('^BFR,9999,33', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeMicroPdf417(Orientation::Rotate0, 10, 0);

        self::assertSame('^BFN,10,0', (string) $command);
    }
}
