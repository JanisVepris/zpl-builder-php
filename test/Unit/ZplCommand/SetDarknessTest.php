<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetDarkness;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetDarkness::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetDarknessTest extends UnitTestCase
{
    public function testDarknessAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDarkness(31);
    }

    public function testDarknessBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetDarkness(-1);
    }

    public function testRendersDarkness(): void
    {
        self::assertSame('~SD15', (string) new SetDarkness(15));
    }

    public function testRendersSingleDigitZeroPadded(): void
    {
        self::assertSame('~SD05', (string) new SetDarkness(5));
    }

    public function testRendersZero(): void
    {
        self::assertSame('~SD00', (string) new SetDarkness(0));
    }
}
