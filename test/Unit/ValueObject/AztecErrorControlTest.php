<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ValueObject;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ValueObject\AztecErrorControl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(AztecErrorControl::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class AztecErrorControlTest extends UnitTestCase
{
    public function testCompactSymbolAboveMaxLayersThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::compactSymbol(5);
    }

    public function testCompactSymbolBelowMinLayersThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::compactSymbol(0);
    }

    public function testCompactSymbolMapsLayersToWireValue(): void
    {
        self::assertSame(101, AztecErrorControl::compactSymbol(1)->value());
        self::assertSame(104, AztecErrorControl::compactSymbol(4)->value());
    }

    public function testDefaultLevelValue(): void
    {
        self::assertSame(0, AztecErrorControl::defaultLevel()->value());
    }

    public function testErrorCorrectionPercentageAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::errorCorrectionPercentage(100);
    }

    public function testErrorCorrectionPercentageBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::errorCorrectionPercentage(0);
    }

    public function testErrorCorrectionPercentageValue(): void
    {
        self::assertSame(1, AztecErrorControl::errorCorrectionPercentage(1)->value());
        self::assertSame(99, AztecErrorControl::errorCorrectionPercentage(99)->value());
    }

    public function testFullRangeSymbolAboveMaxLayersThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::fullRangeSymbol(33);
    }

    public function testFullRangeSymbolBelowMinLayersThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        AztecErrorControl::fullRangeSymbol(0);
    }

    public function testFullRangeSymbolMapsLayersToWireValue(): void
    {
        self::assertSame(201, AztecErrorControl::fullRangeSymbol(1)->value());
        self::assertSame(232, AztecErrorControl::fullRangeSymbol(32)->value());
    }

    public function testRuneValue(): void
    {
        self::assertSame(300, AztecErrorControl::rune()->value());
    }
}
