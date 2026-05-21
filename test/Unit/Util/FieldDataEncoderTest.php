<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Util;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldDataEncoder::class)]
class FieldDataEncoderTest extends UnitTestCase
{
    public function testCleanStringPassesThrough(): void
    {
        self::assertSame('Hello World', FieldDataEncoder::escape('Hello World'));
    }

    public function testCustomIndicatorEscapesItself(): void
    {
        self::assertSame('A%25B%5EC%7ED', FieldDataEncoder::escape('A%B^C~D', '%'));
    }

    public function testCustomIndicatorLeavesDefaultIndicatorAlone(): void
    {
        self::assertSame('A_B%5EC', FieldDataEncoder::escape('A_B^C', '%'));
    }

    public function testEmptyStringPassesThrough(): void
    {
        self::assertSame('', FieldDataEncoder::escape(''));
    }

    public function testEscapesAllSpecialsInOnePass(): void
    {
        self::assertSame('_5E_7E_5F', FieldDataEncoder::escape('^~_'));
    }

    public function testEscapesCaret(): void
    {
        self::assertSame('A_5EB', FieldDataEncoder::escape('A^B'));
    }

    public function testEscapesDefaultIndicator(): void
    {
        self::assertSame('A_5FB', FieldDataEncoder::escape('A_B'));
    }

    public function testEscapesTilde(): void
    {
        self::assertSame('A_7EB', FieldDataEncoder::escape('A~B'));
    }

    public function testMultiByteUtf8PassesThrough(): void
    {
        self::assertSame('héllo', FieldDataEncoder::escape('héllo'));
    }
}
