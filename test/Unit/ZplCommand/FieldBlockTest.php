<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldBlock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldBlock::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Justify::class)]
#[UsesClass(ValueAssert::class)]
class FieldBlockTest extends UnitTestCase
{
    public function testHangingIndentAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(100, 1, 0, Justify::Left, 10000);
    }

    public function testLineSpacingAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(100, 1, 10000, Justify::Left, 0);
    }

    public function testLineSpacingBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(100, 1, -10000, Justify::Left, 0);
    }

    public function testMaxLinesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(100, 10000, 0, Justify::Left, 0);
    }

    public function testMaxLinesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(100, 0, 0, Justify::Left, 0);
    }

    public function testRendersAllParameters(): void
    {
        $command = new FieldBlock(400, 3, 5, Justify::Center, 10);

        self::assertSame('^FB400,3,5,C,10', (string) $command);
    }

    public function testRendersWithNegativeLineSpacing(): void
    {
        $command = new FieldBlock(200, 2, -3, Justify::Left, 0);

        self::assertSame('^FB200,2,-3,L,0', (string) $command);
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldBlock(10000, 1, 0, Justify::Left, 0);
    }
}
