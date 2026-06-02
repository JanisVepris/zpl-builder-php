<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use RangeException;

#[CoversClass(StringLengthOutOfRangeException::class)]
class StringLengthOutOfRangeExceptionTest extends UnitTestCase
{
    public function testExtendsRangeException(): void
    {
        self::assertInstanceOf(RangeException::class, new StringLengthOutOfRangeException(300, 1, 255));
    }

    public function testMessageReportsLengthAndBounds(): void
    {
        $exception = new StringLengthOutOfRangeException(300, 1, 255);

        self::assertSame('String length 300 is out of range. Expected between 1 and 255.', $exception->getMessage());
    }
}
