<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\TearOffAdjust;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(TearOffAdjust::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class TearOffAdjustTest extends UnitTestCase
{
    public function testOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new TearOffAdjust(121);
    }

    public function testOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new TearOffAdjust(-121);
    }

    public function testRendersMaximumNegativeAdjustment(): void
    {
        self::assertSame('~TA-120', (string) new TearOffAdjust(-120));
    }

    public function testRendersNegativeAdjustment(): void
    {
        self::assertSame('~TA-45', (string) new TearOffAdjust(-45));
    }

    public function testRendersPositiveAdjustmentZeroPadded(): void
    {
        self::assertSame('~TA045', (string) new TearOffAdjust(45));
    }

    public function testRendersZero(): void
    {
        self::assertSame('~TA000', (string) new TearOffAdjust(0));
    }
}
