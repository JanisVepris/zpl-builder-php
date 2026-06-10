<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeInterleaved2of5;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeInterleaved2of5::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeInterleaved2of5Test extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeInterleaved2of5(Orientation::Rotate0, 32001, true, false, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeInterleaved2of5(Orientation::Rotate0, 0, true, false, false);
    }

    public function testRendersRotatedWithCheckDigit(): void
    {
        $command = new BarcodeInterleaved2of5(Orientation::Rotate90, 50, true, true, true);

        self::assertSame('^B2R,50,Y,Y,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeInterleaved2of5(Orientation::Rotate0, 100, true, false, false);

        self::assertSame('^B2N,100,Y,N,N', (string) $command);
    }
}
