<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ScalableBitmappedFont;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ScalableBitmappedFont::class)]
#[UsesClass(Font::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ScalableBitmappedFontTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ScalableBitmappedFont(Font::Zero, Orientation::Rotate0, 32001, 50);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ScalableBitmappedFont(Font::Zero, Orientation::Rotate0, 9, 50);
    }

    public function testRendersWithLetterFontAndRotation(): void
    {
        self::assertSame(
            '^AAR,40,20',
            (string) new ScalableBitmappedFont(Font::A, Orientation::Rotate90, 40, 20),
        );
    }

    public function testRendersWithNumericFontAndNormalOrientation(): void
    {
        self::assertSame(
            '^A0N,50,50',
            (string) new ScalableBitmappedFont(Font::Zero, Orientation::Rotate0, 50, 50),
        );
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ScalableBitmappedFont(Font::Zero, Orientation::Rotate0, 50, 32001);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ScalableBitmappedFont(Font::Zero, Orientation::Rotate0, 50, 9);
    }
}
