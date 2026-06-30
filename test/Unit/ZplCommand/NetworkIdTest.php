<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\NetworkId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(NetworkId::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class NetworkIdTest extends UnitTestCase
{
    public function testNetworkIdAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new NetworkId(1000);
    }

    public function testNetworkIdBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new NetworkId(0);
    }

    public function testRendersMaximumNetworkId(): void
    {
        self::assertSame('^NI999', (string) new NetworkId(999));
    }

    public function testRendersZeroPaddedNetworkId(): void
    {
        self::assertSame('^NI042', (string) new NetworkId(42));
    }
}
