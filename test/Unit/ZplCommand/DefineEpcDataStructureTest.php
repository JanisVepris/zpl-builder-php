<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\DefineEpcDataStructure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DefineEpcDataStructure::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class DefineEpcDataStructureTest extends UnitTestCase
{
    public function testPartitionSizeAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DefineEpcDataStructure(96, 65);
    }

    public function testRendersTotalBitSizeOnly(): void
    {
        self::assertSame('^RB96', (string) new DefineEpcDataStructure(96));
    }

    public function testRendersWithPartitions(): void
    {
        self::assertSame('^RB96,10,26,60', (string) new DefineEpcDataStructure(96, 10, 26, 60));
    }

    public function testTotalBitSizeBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DefineEpcDataStructure(0);
    }
}
