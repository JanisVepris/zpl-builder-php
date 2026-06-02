<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(CharacterRemap::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class CharacterRemapTest extends UnitTestCase
{
    public function testRendersSourceAndDestination(): void
    {
        self::assertSame('64,100', (string) new CharacterRemap(64, 100));
    }

    public function testRendersLowerBoundValues(): void
    {
        self::assertSame('0,0', (string) new CharacterRemap(0, 0));
    }

    public function testRendersUpperBoundValues(): void
    {
        self::assertSame('255,255', (string) new CharacterRemap(255, 255));
    }

    public function testExposesSourceAndDestination(): void
    {
        $remap = new CharacterRemap(64, 100);

        self::assertSame(64, $remap->source);
        self::assertSame(100, $remap->destination);
    }

    public function testNegativeSourceThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CharacterRemap(-1, 0);
    }

    public function testSourceAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CharacterRemap(256, 0);
    }

    public function testNegativeDestinationThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CharacterRemap(0, -1);
    }

    public function testDestinationAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CharacterRemap(0, 256);
    }
}
