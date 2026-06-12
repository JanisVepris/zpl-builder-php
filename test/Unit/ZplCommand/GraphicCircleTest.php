<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicCircle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicCircle::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(ValueAssert::class)]
class GraphicCircleTest extends UnitTestCase
{
    public function testDiameterAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicCircle(4096, 1, LineColor::Black);
    }

    public function testDiameterBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicCircle(2, 1, LineColor::Black);
    }

    public function testRendersBlackCircleWithDefaults(): void
    {
        $command = new GraphicCircle(100, 1, LineColor::Black);

        self::assertSame('^GC100,1,B', (string) $command);
    }

    public function testRendersWhiteCircleWithThickBorder(): void
    {
        $command = new GraphicCircle(4095, 4095, LineColor::White);

        self::assertSame('^GC4095,4095,W', (string) $command);
    }

    public function testThicknessAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicCircle(100, 4096, LineColor::Black);
    }

    public function testThicknessBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicCircle(100, 0, LineColor::Black);
    }
}
