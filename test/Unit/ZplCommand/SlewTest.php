<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\Slew;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Slew::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SlewTest extends UnitTestCase
{
    public function testDotRowsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new Slew(32001);
    }

    public function testDotRowsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new Slew(-1);
    }

    public function testRendersWithDotRows(): void
    {
        self::assertSame('^PF50', (string) new Slew(50));
    }
}
