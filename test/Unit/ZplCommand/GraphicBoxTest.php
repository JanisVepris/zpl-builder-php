<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicBox;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicBox::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(ValueAssert::class)]
class GraphicBoxTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicBox(100, 32001, 1, LineColor::Black, 0);
    }

    public function testRendersFilledBox(): void
    {
        $command = new GraphicBox(1140, 1500, 6, LineColor::Black, 0);

        self::assertSame('^GB1140,1500,6,B,0', (string) $command);
    }

    public function testRendersVerticalLine(): void
    {
        $command = new GraphicBox(0, 300, 5, LineColor::Black, 0);

        self::assertSame('^GB0,300,5,B,0', (string) $command);
    }

    public function testRendersWhiteBoxWithRounding(): void
    {
        $command = new GraphicBox(200, 200, 4, LineColor::White, 8);

        self::assertSame('^GB200,200,4,W,8', (string) $command);
    }

    public function testRoundingAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicBox(100, 100, 1, LineColor::Black, 9);
    }

    public function testThicknessAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicBox(100, 100, 32001, LineColor::Black, 0);
    }

    public function testThicknessBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicBox(100, 100, 0, LineColor::Black, 0);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicBox(32001, 100, 1, LineColor::Black, 0);
    }
}
