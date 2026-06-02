<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use InvalidArgumentException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FontPresetDoesNotExistException::class)]
class FontPresetDoesNotExistExceptionTest extends UnitTestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        self::assertInstanceOf(InvalidArgumentException::class, new FontPresetDoesNotExistException('big'));
    }

    public function testMessageIncludesPresetName(): void
    {
        $exception = new FontPresetDoesNotExistException('big');

        self::assertSame('Font preset "big" does not exist.', $exception->getMessage());
    }
}
