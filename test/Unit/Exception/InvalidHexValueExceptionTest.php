<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use Janisvepris\ZplBuilder\Exception\InvalidHexValueException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use UnexpectedValueException;

#[CoversClass(InvalidHexValueException::class)]
class InvalidHexValueExceptionTest extends UnitTestCase
{
    public function testExtendsUnexpectedValueException(): void
    {
        self::assertInstanceOf(UnexpectedValueException::class, new InvalidHexValueException('GG'));
    }

    public function testMessageIncludesOffendingValue(): void
    {
        $exception = new InvalidHexValueException('GG');

        self::assertSame('Invalid hex value: GG. Allowed values are 0-9 and A-F (a-f).', $exception->getMessage());
    }
}
