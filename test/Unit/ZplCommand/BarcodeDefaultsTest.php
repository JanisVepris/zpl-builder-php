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
    public function testFloatFormattingIsLocaleIndependent(): void
    {
        $previous = setlocale(LC_NUMERIC, '0');
        $applied = setlocale(
            LC_NUMERIC,
            'de_DE.UTF-8',
            'de_DE',
            'fr_FR.UTF-8',
            'fr_FR',
            'German_Germany.1252',
            'French_France.1252',
        );

        if ($applied === false || (localeconv()['decimal_point'] ?? '.') === '.') {
            setlocale(LC_NUMERIC, $previous !== false ? $previous : 'C');
            self::markTestSkipped('No comma-decimal locale available on this system.');
        }

        try {
            self::assertSame('^BY2,3.0,75', (string) new BarcodeDefaults(2, 3.0, 75));
            self::assertSame('^BY3,2.5,100', (string) new BarcodeDefaults(3, 2.5, 100));
        } finally {
            setlocale(LC_NUMERIC, $previous !== false ? $previous : 'C');
        }
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(2, 3.0, 0);
    }

    public function testModuleWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(11, 3.0, 100);
    }

    public function testModuleWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDefaults(0, 3.0, 100);
    }

    public function testRatioAboveMaxThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaults(2, 3.1, 100);
    }

    public function testRatioBelowMinThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeDefaults(2, 1.9, 100);
    }

    public function testRendersAllParameters(): void
    {
        self::assertSame('^BY2,3.0,75', (string) new BarcodeDefaults(2, 3.0, 75));
    }

    public function testRendersFractionalRatio(): void
    {
        self::assertSame('^BY3,2.5,100', (string) new BarcodeDefaults(3, 2.5, 100));
    }
}
