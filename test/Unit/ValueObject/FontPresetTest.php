<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ValueObject;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FontPreset::class)]
class FontPresetTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontPreset(Font::A, 32001, 10);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontPreset(Font::A, -1, 10);
    }

    public function testStoresProperties(): void
    {
        $preset = new FontPreset(Font::B, 30, 15);

        self::assertSame(Font::B, $preset->font);
        self::assertSame(30, $preset->height);
        self::assertSame(15, $preset->width);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontPreset(Font::A, 10, 32001);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontPreset(Font::A, 10, -1);
    }
}
