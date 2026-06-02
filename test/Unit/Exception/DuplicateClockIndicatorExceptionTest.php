<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use InvalidArgumentException;
use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DuplicateClockIndicatorException::class)]
class DuplicateClockIndicatorExceptionTest extends UnitTestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        self::assertInstanceOf(InvalidArgumentException::class, new DuplicateClockIndicatorException('M', 'month', 'minute'));
    }

    public function testMessageDescribesConflict(): void
    {
        $exception = new DuplicateClockIndicatorException('M', 'month', 'minute');

        self::assertSame(
            'Clock indicator "M" used for month conflicts with the minute indicator; ^FC requires each indicator to be distinct.',
            $exception->getMessage(),
        );
    }
}
