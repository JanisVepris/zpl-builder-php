<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePlessey;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodePlessey::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodePlesseyTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePlessey(Orientation::Rotate0, false, 32001, true, false);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodePlessey(Orientation::Rotate0, false, 0, true, false);
    }

    public function testRendersRotatedWithCheckDigitAndInterpretationAbove(): void
    {
        $command = new BarcodePlessey(Orientation::Rotate90, true, 50, true, true);

        self::assertSame('^BPR,Y,50,Y,Y', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodePlessey(Orientation::Rotate0, false, 100, true, false);

        self::assertSame('^BPN,N,100,Y,N', (string) $command);
    }
}
