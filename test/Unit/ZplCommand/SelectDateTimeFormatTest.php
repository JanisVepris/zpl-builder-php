<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DateTimeFormat;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SelectDateTimeFormat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SelectDateTimeFormat::class)]
#[UsesClass(DateTimeFormat::class)]
class SelectDateTimeFormatTest extends UnitTestCase
{
    public function testRendersDayMonthYear12Hour(): void
    {
        self::assertSame('^KD4', (string) new SelectDateTimeFormat(DateTimeFormat::DayMonthYear12Hour));
    }

    public function testRendersDayMonthYear24Hour(): void
    {
        self::assertSame('^KD3', (string) new SelectDateTimeFormat(DateTimeFormat::DayMonthYear24Hour));
    }

    public function testRendersMonthDayYear12Hour(): void
    {
        self::assertSame('^KD2', (string) new SelectDateTimeFormat(DateTimeFormat::MonthDayYear12Hour));
    }

    public function testRendersMonthDayYear24Hour(): void
    {
        self::assertSame('^KD1', (string) new SelectDateTimeFormat(DateTimeFormat::MonthDayYear24Hour));
    }

    public function testRendersVersionNumber(): void
    {
        self::assertSame('^KD0', (string) new SelectDateTimeFormat(DateTimeFormat::VersionNumber));
    }
}
