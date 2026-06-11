<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeEan8;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeEan8::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeEan8Test extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeEan8(Orientation::Rotate0, 32001, true, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeEan8(Orientation::Rotate0, 0, true, false);
    }

    public function testRendersRotatedWithInterpretationAbove(): void
    {
        $command = new BarcodeEan8(Orientation::Rotate90, 50, true, true);

        self::assertSame('^B8R,50,Y,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeEan8(Orientation::Rotate0, 100, true, false);

        self::assertSame('^B8N,100,Y,N', (string) $command);
    }
}
