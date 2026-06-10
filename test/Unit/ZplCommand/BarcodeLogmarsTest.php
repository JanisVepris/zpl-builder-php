<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeLogmars;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeLogmars::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeLogmarsTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeLogmars(Orientation::Rotate0, 32001, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeLogmars(Orientation::Rotate0, 0, false);
    }

    public function testRendersRotatedWithInterpretationAbove(): void
    {
        $command = new BarcodeLogmars(Orientation::Rotate90, 50, true);

        self::assertSame('^BLR,50,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeLogmars(Orientation::Rotate0, 100, false);

        self::assertSame('^BLN,100,N', (string) $command);
    }
}
