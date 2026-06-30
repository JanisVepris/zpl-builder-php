<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CacheType;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\CacheOn;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(CacheOn::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class CacheOnTest extends UnitTestCase
{
    public function testAdditionalMemoryAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CacheOn(true, 32001, CacheType::Normal);
    }

    public function testAdditionalMemoryBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new CacheOn(true, -1, CacheType::Normal);
    }

    public function testRendersDisabledWithInternalBuffer(): void
    {
        $command = new CacheOn(false, 128, CacheType::Internal);

        self::assertSame('^CON,128,1', (string) $command);
    }

    public function testRendersEnabledWithNormalBuffer(): void
    {
        $command = new CacheOn(true, 40, CacheType::Normal);

        self::assertSame('^COY,40,0', (string) $command);
    }
}
