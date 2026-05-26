<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintWidth;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintWidth::class)]
class PrintWidthTest extends UnitTestCase
{
    public function testRendersMinimumWidth(): void
    {
        self::assertSame('^PW2', (string) new PrintWidth(2));
    }

    public function testRendersWithWidth(): void
    {
        self::assertSame('^PW1160', (string) new PrintWidth(1160));
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrintWidth(32001);
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrintWidth(1);
    }
}
