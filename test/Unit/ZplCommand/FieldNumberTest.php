<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldNumber::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class FieldNumberTest extends UnitTestCase
{
    public function testNegativeNumberThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldNumber(-1);
    }

    public function testNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldNumber(10000);
    }

    public function testRendersWithNumber(): void
    {
        self::assertSame('^FN42', (string) new FieldNumber(42));
    }
}
