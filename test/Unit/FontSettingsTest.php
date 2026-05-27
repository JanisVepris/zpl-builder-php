<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\FontSettings;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FontSettings::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class FontSettingsTest extends UnitTestCase
{
    public function testConstructorRejectsHeightAboveMax(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontSettings(32001, 5);
    }

    public function testConstructorRejectsHeightBelowMin(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontSettings(-1, 5);
    }

    public function testConstructorRejectsWidthAboveMax(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontSettings(9, 32001);
    }

    public function testConstructorRejectsWidthBelowMin(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontSettings(9, -1);
    }

    public function testDefaultsArePersistedAndReadable(): void
    {
        $settings = new FontSettings();

        self::assertSame(9, $settings->height());
        self::assertSame(5, $settings->width());
    }

    public function testSettersRejectOutOfRange(): void
    {
        $settings = new FontSettings();

        $this->expectException(IntegerValueOutOfRangeException::class);

        $settings->setHeight(-1);
    }
}
