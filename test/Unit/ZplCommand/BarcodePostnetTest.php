<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePostnet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodePostnet::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodePostnetTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePostnet(Orientation::Rotate0, 32001, false, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePostnet(Orientation::Rotate0, 0, false, false);
    }

    public function testRendersRotatedWithInterpretation(): void
    {
        $command = new BarcodePostnet(Orientation::Rotate90, 50, true, true);

        self::assertSame('^BZR,50,Y,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodePostnet(Orientation::Rotate0, 100, false, false);

        self::assertSame('^BZN,100,N,N', (string) $command);
    }
}
