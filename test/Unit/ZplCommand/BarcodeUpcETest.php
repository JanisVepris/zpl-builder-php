<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeUpcE;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeUpcE::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeUpcETest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeUpcE(Orientation::Rotate0, 32001, true, false, true);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeUpcE(Orientation::Rotate0, 0, true, false, true);
    }

    public function testRendersRotatedWithoutCheckDigit(): void
    {
        $command = new BarcodeUpcE(Orientation::Rotate90, 50, true, true, false);

        self::assertSame('^B9R,50,Y,Y,N', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeUpcE(Orientation::Rotate0, 100, true, false, true);

        self::assertSame('^B9N,100,Y,N,Y', (string) $command);
    }
}
