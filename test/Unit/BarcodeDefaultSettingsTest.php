<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\BarcodeDefaultSettings;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BarcodeDefaultSettings::class)]
class BarcodeDefaultSettingsTest extends UnitTestCase
{
    public function testConstructorRejectsHeightBelowMin(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaultSettings(2, 3.0, 0);
    }

    public function testConstructorRejectsModuleWidthAboveMax(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaultSettings(11, 3.0, 10);
    }

    public function testConstructorRejectsModuleWidthBelowMin(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaultSettings(0, 3.0, 10);
    }

    public function testConstructorRejectsRatioAboveMax(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaultSettings(2, 3.1, 10);
    }

    public function testConstructorRejectsRatioBelowMin(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaultSettings(2, 1.9, 10);
    }

    public function testDefaultsArePersistedAndReadable(): void
    {
        $settings = new BarcodeDefaultSettings();

        self::assertSame(2, $settings->moduleWidth());
        self::assertSame(3.0, $settings->wideToNarrowRatio());
        self::assertSame(10, $settings->height());
    }
}
