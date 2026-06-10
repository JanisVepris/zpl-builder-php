<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CodabarCharacter;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCodabar;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeCodabar::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(CodabarCharacter::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeCodabarTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodabar(Orientation::Rotate0, 32001, true, false, CodabarCharacter::A, CodabarCharacter::A);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCodabar(Orientation::Rotate0, 0, true, false, CodabarCharacter::A, CodabarCharacter::A);
    }

    public function testRendersRotatedWithCustomStartStop(): void
    {
        $command = new BarcodeCodabar(Orientation::Rotate90, 50, true, true, CodabarCharacter::C, CodabarCharacter::D);

        self::assertSame('^BKR,N,50,Y,Y,C,D', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeCodabar(Orientation::Rotate0, 100, true, false, CodabarCharacter::A, CodabarCharacter::A);

        self::assertSame('^BKN,N,100,Y,N,A,A', (string) $command);
    }
}
