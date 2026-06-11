<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicEllipse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicEllipse::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(ValueAssert::class)]
class GraphicEllipseTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(100, 4096, 1, LineColor::Black);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(100, 2, 1, LineColor::Black);
    }

    public function testRendersBlackEllipseWithDefaults(): void
    {
        $command = new GraphicEllipse(300, 200, 1, LineColor::Black);

        self::assertSame('^GE300,200,1,B', (string) $command);
    }

    public function testRendersWhiteEllipseWithThickBorder(): void
    {
        $command = new GraphicEllipse(4095, 4095, 4095, LineColor::White);

        self::assertSame('^GE4095,4095,4095,W', (string) $command);
    }

    public function testThicknessAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(100, 100, 4096, LineColor::Black);
    }

    public function testThicknessBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(100, 100, 0, LineColor::Black);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(4096, 100, 1, LineColor::Black);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicEllipse(2, 100, 1, LineColor::Black);
    }
}
