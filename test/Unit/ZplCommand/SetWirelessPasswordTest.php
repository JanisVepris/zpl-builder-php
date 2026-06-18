<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetWirelessPassword;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetWirelessPassword::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetWirelessPasswordTest extends UnitTestCase
{
    public function testNewPasswordAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetWirelessPassword(0, 10000);
    }

    public function testOldPasswordBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetWirelessPassword(-1, 0);
    }

    public function testRendersBothPasswords(): void
    {
        self::assertSame('^WP1234,5678', (string) new SetWirelessPassword(1234, 5678));
    }

    public function testRendersZeroPadded(): void
    {
        self::assertSame('^WP0000,1234', (string) new SetWirelessPassword(0, 1234));
    }
}
