<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintQuantity;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintQuantity::class)]
class PrintQuantityTest extends UnitTestCase
{
    public function testRendersWithQuantity(): void
    {
        self::assertSame('^PQ5', (string) new PrintQuantity(5));
    }

    public function testZeroQuantityThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrintQuantity(0);
    }

    public function testQuantityAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new PrintQuantity(100000000);
    }
}
