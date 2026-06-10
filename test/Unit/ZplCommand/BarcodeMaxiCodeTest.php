<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MaxiCodeMode;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMaxiCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeMaxiCode::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(MaxiCodeMode::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeMaxiCodeTest extends UnitTestCase
{
    public function testRendersStructuredAppend(): void
    {
        $command = new BarcodeMaxiCode(MaxiCodeMode::StructuredCarrierAlphanumeric, 3, 8);

        self::assertSame('^BD3,3,8', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeMaxiCode(MaxiCodeMode::StructuredCarrierNumeric, 1, 1);

        self::assertSame('^BD2,1,1', (string) $command);
    }

    public function testSymbolNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMaxiCode(MaxiCodeMode::StandardSymbol, 9, 1);
    }

    public function testSymbolNumberBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMaxiCode(MaxiCodeMode::StandardSymbol, 0, 1);
    }

    public function testTotalSymbolsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMaxiCode(MaxiCodeMode::StandardSymbol, 1, 9);
    }

    public function testTotalSymbolsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMaxiCode(MaxiCodeMode::StandardSymbol, 1, 0);
    }
}
