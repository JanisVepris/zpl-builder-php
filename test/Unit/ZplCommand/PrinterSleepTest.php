<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\PrinterSleep;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PrinterSleep::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class PrinterSleepTest extends UnitTestCase
{
    public function testIdleSecondsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrinterSleep(1000000, false);
    }

    public function testIdleSecondsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrinterSleep(-1, false);
    }

    public function testRendersIdleSeconds(): void
    {
        self::assertSame('^ZZ300,N', (string) new PrinterSleep(300, false));
    }

    public function testRendersShutdownWithLabelsQueued(): void
    {
        self::assertSame('^ZZ0,Y', (string) new PrinterSleep(0, true));
    }
}
