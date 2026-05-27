<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\LabelLength;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(LabelLength::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class LabelLengthTest extends UnitTestCase
{
    public function testLengthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelLength(32001);
    }

    public function testLengthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelLength(0);
    }

    public function testRendersWithLength(): void
    {
        self::assertSame('^LL1520', (string) new LabelLength(1520));
    }
}
