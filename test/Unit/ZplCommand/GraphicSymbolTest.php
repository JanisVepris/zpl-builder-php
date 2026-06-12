<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicSymbol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicSymbol::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(ValueAssert::class)]
class GraphicSymbolTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicSymbol(Orientation::Rotate0, 32001, 50);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicSymbol(Orientation::Rotate0, -1, 50);
    }

    public function testRendersNormalOrientation(): void
    {
        $command = new GraphicSymbol(Orientation::Rotate0, 50, 40);

        self::assertSame('^GSN,50,40', (string) $command);
    }

    public function testRendersRotatedSymbol(): void
    {
        $command = new GraphicSymbol(Orientation::Rotate90, 27, 27);

        self::assertSame('^GSR,27,27', (string) $command);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicSymbol(Orientation::Rotate0, 50, 32001);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicSymbol(Orientation::Rotate0, 50, -1);
    }
}
