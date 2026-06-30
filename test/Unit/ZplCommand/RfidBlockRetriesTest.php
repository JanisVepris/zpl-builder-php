<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\RfidBlockRetries;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(RfidBlockRetries::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class RfidBlockRetriesTest extends UnitTestCase
{
    public function testRendersRetries(): void
    {
        self::assertSame('^RR5', (string) new RfidBlockRetries(5));
    }

    public function testRendersZero(): void
    {
        self::assertSame('^RR0', (string) new RfidBlockRetries(0));
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RfidBlockRetries(11);
    }

    public function testRetriesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RfidBlockRetries(-1);
    }
}
