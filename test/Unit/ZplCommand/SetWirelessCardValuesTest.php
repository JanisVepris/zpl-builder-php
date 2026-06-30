<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WirelessOperatingMode;
use Janisvepris\ZplBuilder\Enum\WirelessPreamble;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetWirelessCardValues;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetWirelessCardValues::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SetWirelessCardValuesTest extends UnitTestCase
{
    public function testEssidTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SetWirelessCardValues(str_repeat('a', 33), WirelessOperatingMode::Infrastructure, WirelessPreamble::Long);
    }

    public function testEssidWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SetWirelessCardValues('net,work', WirelessOperatingMode::Infrastructure, WirelessPreamble::Long);
    }

    public function testRendersAdhocShortPreamble(): void
    {
        $command = new SetWirelessCardValues('mynet', WirelessOperatingMode::Adhoc, WirelessPreamble::Short);

        self::assertSame('^WSmynet,A,S', (string) $command);
    }

    public function testRendersInfrastructureLongPreamble(): void
    {
        $command = new SetWirelessCardValues('mynet', WirelessOperatingMode::Infrastructure, WirelessPreamble::Long);

        self::assertSame('^WSmynet,I,L', (string) $command);
    }
}
