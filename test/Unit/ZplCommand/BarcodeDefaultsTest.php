<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeDefaults;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BarcodeDefaults::class)]
class BarcodeDefaultsTest extends UnitTestCase
{
    public function testRendersAllParameters(): void
    {
        self::assertSame('^BY2,3.0,75', (string) new BarcodeDefaults(2, 3.0, 75));
    }

    public function testRendersFractionalRatio(): void
    {
        self::assertSame('^BY3,2.5,100', (string) new BarcodeDefaults(3, 2.5, 100));
    }

    public function testModuleWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(0, 3.0, 100);
    }

    public function testModuleWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(11, 3.0, 100);
    }

    public function testRatioBelowMinThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaults(2, 1.9, 100);
    }

    public function testRatioAboveMaxThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaults(2, 3.1, 100);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(2, 3.0, 0);
    }
}
