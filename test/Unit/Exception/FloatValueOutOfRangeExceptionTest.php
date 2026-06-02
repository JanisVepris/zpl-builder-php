<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use RangeException;

#[CoversClass(FloatValueOutOfRangeException::class)]
class FloatValueOutOfRangeExceptionTest extends UnitTestCase
{
    public function testExtendsRangeException(): void
    {
        self::assertInstanceOf(RangeException::class, new FloatValueOutOfRangeException(5.5, 0.0, 1.0));
    }

    public function testMessageReportsValueAndBounds(): void
    {
        $exception = new FloatValueOutOfRangeException(5.5, 0.0, 1.0);

        self::assertSame('Float value 5.5 is out of range. Expected between 0 and 1.', $exception->getMessage());
    }
}
