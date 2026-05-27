<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ChangeFont;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ChangeFont::class)]
#[UsesClass(Font::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ChangeFontTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ChangeFont(Font::A, 32001, 15);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ChangeFont(Font::A, -1, 15);
    }

    public function testRendersWithLetterFont(): void
    {
        self::assertSame('^CFA,30,15', (string) new ChangeFont(Font::A, 30, 15));
    }

    public function testRendersWithNumericFont(): void
    {
        self::assertSame('^CF0,42,5', (string) new ChangeFont(Font::Zero, 42, 5));
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ChangeFont(Font::A, 30, 32001);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ChangeFont(Font::A, 30, -1);
    }
}
