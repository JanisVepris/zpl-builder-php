<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\StartPrint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(StartPrint::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class StartPrintTest extends UnitTestCase
{
    public function testDotRowAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new StartPrint(32001);
    }

    public function testDotRowBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new StartPrint(-1);
    }

    public function testRendersDotRow(): void
    {
        self::assertSame('^SP500', (string) new StartPrint(500));
    }
}
