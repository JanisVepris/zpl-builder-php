<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeAztec;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeAztec::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeAztecTest extends UnitTestCase
{
    public function testErrorControlAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 1, false, 301, false, 1, '');
    }

    public function testIdAboveMaxBytesThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 1, false, 0, false, 1, str_repeat('A', 25));
    }

    public function testIdContainingBannedCharacterThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new BarcodeAztec(Orientation::Rotate0, 1, false, 0, false, 1, 'A,B');
    }

    public function testMagnificationAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 11, false, 0, false, 1, '');
    }

    public function testMagnificationBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 0, false, 0, false, 1, '');
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeAztec(Orientation::Rotate0, 1, false, 0, false, 1, '');

        self::assertSame('^B0N,1,N,0,N,1', (string) $command);
    }

    public function testRendersWithStructuredAppendId(): void
    {
        $command = new BarcodeAztec(Orientation::Rotate90, 7, true, 232, true, 26, 'JOB42');

        self::assertSame('^B0R,7,Y,232,Y,26,JOB42', (string) $command);
    }

    public function testSymbolCountAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 1, false, 0, false, 27, '');
    }

    public function testSymbolCountBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeAztec(Orientation::Rotate0, 1, false, 0, false, 0, '');
    }
}
