<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\WebAuthenticationTimeout;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(WebAuthenticationTimeout::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class WebAuthenticationTimeoutTest extends UnitTestCase
{
    public function testMinutesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new WebAuthenticationTimeout(256);
    }

    public function testMinutesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new WebAuthenticationTimeout(-1);
    }

    public function testRendersMinutes(): void
    {
        self::assertSame('^NW10', (string) new WebAuthenticationTimeout(10));
    }

    public function testRendersZero(): void
    {
        self::assertSame('^NW0', (string) new WebAuthenticationTimeout(0));
    }
}
