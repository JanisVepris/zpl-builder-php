<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\IpResolution;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ChangeWirelessNetworkSettings;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ChangeWirelessNetworkSettings::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class ChangeWirelessNetworkSettingsTest extends UnitTestCase
{
    public function testIpAddressWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new ChangeWirelessNetworkSettings(IpResolution::Permanent, '192.168.0,1', '', '', null, null, null, null, null);
    }

    public function testPortNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ChangeWirelessNetworkSettings(IpResolution::Dhcp, '', '', '', null, null, null, null, 100000);
    }

    public function testRendersCoreSettings(): void
    {
        $command = new ChangeWirelessNetworkSettings(
            IpResolution::Permanent,
            '192.168.0.1',
            '255.255.255.0',
            '192.168.0.2',
            null,
            null,
            null,
            null,
            null,
        );

        self::assertSame('^WIP,192.168.0.1,255.255.255.0,192.168.0.2', (string) $command);
    }

    public function testRendersTrailingParametersWithInteriorGap(): void
    {
        $command = new ChangeWirelessNetworkSettings(
            IpResolution::Dhcp,
            '',
            '',
            '',
            null,
            null,
            null,
            null,
            9100,
        );

        self::assertSame('^WID,,,,,,,,9100', (string) $command);
    }
}
