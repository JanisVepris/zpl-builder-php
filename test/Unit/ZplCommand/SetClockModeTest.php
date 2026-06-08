<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockLanguage;
use Janisvepris\ZplBuilder\Enum\ClockMode;
use Janisvepris\ZplBuilder\Exception\ConflictingClockModeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetClockMode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetClockMode::class)]
#[UsesClass(ConflictingClockModeException::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetClockModeTest extends UnitTestCase
{
    public function testBothModeAndToleranceThrows(): void
    {
        $this->expectException(ConflictingClockModeException::class);

        new SetClockMode(ClockMode::StartTime, 30, null);
    }

    public function testRendersLanguageWithStartMode(): void
    {
        $command = new SetClockMode(ClockMode::StartTime, null, ClockLanguage::German);

        self::assertSame('^SLS,4', (string) $command);
    }

    public function testRendersStartMode(): void
    {
        $command = new SetClockMode(ClockMode::StartTime, null, null);

        self::assertSame('^SLS', (string) $command);
    }

    public function testRendersTimeNowMode(): void
    {
        $command = new SetClockMode(ClockMode::TimeNow, null, null);

        self::assertSame('^SLT', (string) $command);
    }

    public function testRendersToleranceWithLanguage(): void
    {
        $command = new SetClockMode(null, 30, ClockLanguage::English);

        self::assertSame('^SL30,1', (string) $command);
    }

    public function testRendersToleranceWithoutLanguage(): void
    {
        $command = new SetClockMode(null, 60, null);

        self::assertSame('^SL60', (string) $command);
    }

    public function testRendersTwoDigitLanguage(): void
    {
        $command = new SetClockMode(ClockMode::StartTime, null, ClockLanguage::Finnish);

        self::assertSame('^SLS,12', (string) $command);
    }

    public function testToleranceAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetClockMode(null, 1000, null);
    }

    public function testToleranceBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetClockMode(null, -1, null);
    }
}
