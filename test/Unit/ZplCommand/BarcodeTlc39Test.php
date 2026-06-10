<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeTlc39;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeTlc39::class)]
#[UsesClass(FloatValueOutOfRangeException::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeTlc39Test extends UnitTestCase
{
    public function testCode39HeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeTlc39(Orientation::Rotate0, 2, 2.0, 10000, 2, 4);
    }

    public function testCode39WidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeTlc39(Orientation::Rotate0, 11, 2.0, 40, 2, 4);
    }

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
            self::assertSame('^BTN,2,2.5,40,2,4', (string) new BarcodeTlc39(Orientation::Rotate0, 2, 2.5, 40, 2, 4));
        } finally {
            setlocale(LC_NUMERIC, $previous !== false ? $previous : 'C');
        }
    }

    public function testMicroPdfRowHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeTlc39(Orientation::Rotate0, 2, 2.0, 40, 2, 256);
    }

    public function testRatioAboveMaxThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeTlc39(Orientation::Rotate0, 2, 3.1, 40, 2, 4);
    }

    public function testRatioBelowMinThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        new BarcodeTlc39(Orientation::Rotate0, 2, 1.9, 40, 2, 4);
    }

    public function testRendersRotatedFullySpecified(): void
    {
        $command = new BarcodeTlc39(Orientation::Rotate90, 4, 3.0, 120, 4, 8);

        self::assertSame('^BTR,4,3.0,120,4,8', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeTlc39(Orientation::Rotate0, 2, 2.0, 40, 2, 4);

        self::assertSame('^BTN,2,2.0,40,2,4', (string) $command);
    }
}
