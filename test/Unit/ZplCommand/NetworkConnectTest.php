<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\NetworkConnect;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(NetworkConnect::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class NetworkConnectTest extends UnitTestCase
{
    public function testIdAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new NetworkConnect(1000);
    }

    public function testIdBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new NetworkConnect(0);
    }

    public function testRendersThreeDigitId(): void
    {
        self::assertSame('~NC005', (string) new NetworkConnect(5));
    }
}
