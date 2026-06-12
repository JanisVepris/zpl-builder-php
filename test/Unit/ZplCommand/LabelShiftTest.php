<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\LabelShift;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(LabelShift::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class LabelShiftTest extends UnitTestCase
{
    public function testRendersNegativeShift(): void
    {
        self::assertSame('^LS-50', (string) new LabelShift(-50));
    }

    public function testRendersPositiveShift(): void
    {
        self::assertSame('^LS120', (string) new LabelShift(120));
    }

    public function testShiftAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelShift(10000);
    }

    public function testShiftBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelShift(-10000);
    }
}
