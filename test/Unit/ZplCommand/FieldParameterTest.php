<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintDirection;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldParameter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldParameter::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(PrintDirection::class)]
#[UsesClass(ValueAssert::class)]
class FieldParameterTest extends UnitTestCase
{
    public function testGapAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldParameter(PrintDirection::Horizontal, 10000);
    }

    public function testGapBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldParameter(PrintDirection::Horizontal, -1);
    }

    public function testRendersHorizontalWithNoGap(): void
    {
        $command = new FieldParameter(PrintDirection::Horizontal, 0);

        self::assertSame('^FPH,0', (string) $command);
    }

    public function testRendersReverseWithNoGap(): void
    {
        $command = new FieldParameter(PrintDirection::Reverse, 0);

        self::assertSame('^FPR,0', (string) $command);
    }

    public function testRendersVerticalWithGap(): void
    {
        $command = new FieldParameter(PrintDirection::Vertical, 30);

        self::assertSame('^FPV,30', (string) $command);
    }
}
