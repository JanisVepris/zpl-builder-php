<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MsiCheckDigit;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMsi;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeMsi::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(MsiCheckDigit::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeMsiTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMsi(Orientation::Rotate0, MsiCheckDigit::OneMod10, 32001, true, false, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeMsi(Orientation::Rotate0, MsiCheckDigit::OneMod10, 0, true, false, false);
    }

    public function testRendersRotatedFullySpecified(): void
    {
        $command = new BarcodeMsi(Orientation::Rotate90, MsiCheckDigit::OneMod11AndOneMod10, 50, true, true, true);

        self::assertSame('^BMR,D,50,Y,Y,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeMsi(Orientation::Rotate0, MsiCheckDigit::OneMod10, 100, true, false, false);

        self::assertSame('^BMN,B,100,Y,N,N', (string) $command);
    }
}
