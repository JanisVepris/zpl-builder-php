<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidPowerLevel;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetRfidPowerLevels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetRfidPowerLevels::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetRfidPowerLevelsTest extends UnitTestCase
{
    public function testAntennaAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetRfidPowerLevels(RfidPowerLevel::High, RfidPowerLevel::High, 3);
    }

    public function testAntennaBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetRfidPowerLevels(RfidPowerLevel::High, RfidPowerLevel::High, 0);
    }

    public function testRendersReadAndWritePower(): void
    {
        $command = new SetRfidPowerLevels(RfidPowerLevel::High, RfidPowerLevel::High, null);

        self::assertSame('^RWH,H', (string) $command);
    }

    public function testRendersWithAntenna(): void
    {
        $command = new SetRfidPowerLevels(RfidPowerLevel::Medium, RfidPowerLevel::Low, 2);

        self::assertSame('^RWM,L,2', (string) $command);
    }
}
