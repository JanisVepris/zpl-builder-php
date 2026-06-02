<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use InvalidArgumentException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StringValueContainsBannedValuesException::class)]
class StringValueContainsBannedValuesExceptionTest extends UnitTestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        self::assertInstanceOf(InvalidArgumentException::class, new StringValueContainsBannedValuesException('a^b', '^'));
    }

    public function testMessageIncludesValueAndForbiddenSubstring(): void
    {
        $exception = new StringValueContainsBannedValuesException('a^b', '^');

        self::assertSame('String value "a^b" contains forbidden substring: ^', $exception->getMessage());
    }
}
