<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use RangeException;

#[CoversClass(IntegerValueOutOfRangeException::class)]
class IntegerValueOutOfRangeExceptionTest extends UnitTestCase
{
    public function testExtendsRangeException(): void
    {
        self::assertInstanceOf(RangeException::class, new IntegerValueOutOfRangeException(50, 0, 10));
    }

    public function testMessageReportsValueAndBounds(): void
    {
        $exception = new IntegerValueOutOfRangeException(50, 0, 10);

        self::assertSame('Integer value 50 is out of range. Expected between 0 and 10.', $exception->getMessage());
    }
}
