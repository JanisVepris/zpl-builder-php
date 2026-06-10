<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeUpcEanExtensions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeUpcEanExtensions::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeUpcEanExtensionsTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeUpcEanExtensions(Orientation::Rotate0, 32001, true, true);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeUpcEanExtensions(Orientation::Rotate0, 0, true, true);
    }

    public function testRendersRotatedWithInterpretationBelow(): void
    {
        $command = new BarcodeUpcEanExtensions(Orientation::Rotate90, 50, true, false);

        self::assertSame('^BSR,50,Y,N', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeUpcEanExtensions(Orientation::Rotate0, 100, true, true);

        self::assertSame('^BSN,100,Y,Y', (string) $command);
    }
}
