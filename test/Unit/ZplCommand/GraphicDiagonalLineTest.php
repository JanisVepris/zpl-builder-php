<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DiagonalOrientation;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicDiagonalLine;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicDiagonalLine::class)]
#[UsesClass(DiagonalOrientation::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(ValueAssert::class)]
class GraphicDiagonalLineTest extends UnitTestCase
{
    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicDiagonalLine(100, 2, 1, LineColor::Black, DiagonalOrientation::RightLeaning);
    }

    public function testRendersLeftLeaningWhiteLine(): void
    {
        $command = new GraphicDiagonalLine(300, 200, 3, LineColor::White, DiagonalOrientation::LeftLeaning);

        self::assertSame('^GD300,200,3,W,L', (string) $command);
    }

    public function testRendersRightLeaningBlackLine(): void
    {
        $command = new GraphicDiagonalLine(100, 100, 1, LineColor::Black, DiagonalOrientation::RightLeaning);

        self::assertSame('^GD100,100,1,B,R', (string) $command);
    }

    public function testThicknessBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicDiagonalLine(100, 100, 0, LineColor::Black, DiagonalOrientation::RightLeaning);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicDiagonalLine(32001, 100, 1, LineColor::Black, DiagonalOrientation::RightLeaning);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicDiagonalLine(2, 100, 1, LineColor::Black, DiagonalOrientation::RightLeaning);
    }
}
