<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\EnableEasBit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(EnableEasBit::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class EnableEasBitTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^REN,0', (string) new EnableEasBit(false, 0));
    }

    public function testRendersEnabledWithRetries(): void
    {
        self::assertSame('^REY,5', (string) new EnableEasBit(true, 5));
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new EnableEasBit(true, 11);
    }
}
