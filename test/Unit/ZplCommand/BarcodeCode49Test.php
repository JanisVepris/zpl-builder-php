<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Code49InterpretationLine;
use Janisvepris\ZplBuilder\Enum\Code49Mode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode49;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeCode49::class)]
#[UsesClass(Code49InterpretationLine::class)]
#[UsesClass(Code49Mode::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeCode49Test extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCode49(Orientation::Rotate0, 32001, Code49InterpretationLine::None, Code49Mode::Automatic);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCode49(Orientation::Rotate0, 0, Code49InterpretationLine::None, Code49Mode::Automatic);
    }

    public function testRendersRotatedWithInterpretationLineAndMode(): void
    {
        $command = new BarcodeCode49(
            Orientation::Rotate90,
            20,
            Code49InterpretationLine::Above,
            Code49Mode::RegularNumeric,
        );

        self::assertSame('^B4R,20,A,2', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeCode49(
            Orientation::Rotate0,
            20,
            Code49InterpretationLine::None,
            Code49Mode::Automatic,
        );

        self::assertSame('^B4N,20,N,A', (string) $command);
    }
}
