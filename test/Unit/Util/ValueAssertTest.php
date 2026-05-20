<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Util;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\InvalidHexValueException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ValueAssert::class)]
class ValueAssertTest extends UnitTestCase
{
    public function testIntWithinDefaultRangePasses(): void
    {
        ValueAssert::int(100);

        $this->expectNotToPerformAssertions();
    }

    public function testIntAtBoundariesPasses(): void
    {
        ValueAssert::int(0);
        ValueAssert::int(32000);

        $this->expectNotToPerformAssertions();
    }

    public function testIntBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        ValueAssert::int(-1);
    }

    public function testIntAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        ValueAssert::int(32001);
    }

    public function testIntRespectsCustomRange(): void
    {
        ValueAssert::int(5, 1, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testIntOutsideCustomRangeThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        ValueAssert::int(11, 1, 10);
    }

    public function testFloatWithinDefaultRangePasses(): void
    {
        ValueAssert::float(123.45);

        $this->expectNotToPerformAssertions();
    }

    public function testFloatBelowMinThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        ValueAssert::float(-0.1);
    }

    public function testFloatAboveMaxThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        ValueAssert::float(32000.1);
    }

    public function testFloatRespectsCustomRange(): void
    {
        ValueAssert::float(2.5, 2.0, 3.0);

        $this->expectNotToPerformAssertions();
    }

    public function testStringLengthWithinRangePasses(): void
    {
        ValueAssert::stringLength('hello', 1, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testEmptyStringPassesWithZeroMin(): void
    {
        ValueAssert::stringLength('', 0, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testStringTooShortThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        ValueAssert::stringLength('hi', 3, 10);
    }

    public function testStringTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        ValueAssert::stringLength('hello world', 1, 5);
    }

    public function testStringLengthUsesMultiByteCount(): void
    {
        ValueAssert::stringLength('héllo', 5, 5);

        $this->expectNotToPerformAssertions();
    }

    public function testHexValueAcceptsDigits(): void
    {
        ValueAssert::hexValue('0123456789');

        $this->expectNotToPerformAssertions();
    }

    public function testHexValueAcceptsLowercaseHex(): void
    {
        ValueAssert::hexValue('abcdef');

        $this->expectNotToPerformAssertions();
    }

    public function testHexValueAcceptsUppercaseHex(): void
    {
        ValueAssert::hexValue('ABCDEF');

        $this->expectNotToPerformAssertions();
    }

    public function testHexValueRejectsNonHexCharacters(): void
    {
        $this->expectException(InvalidHexValueException::class);

        ValueAssert::hexValue('g0');
    }

    public function testHexValueRejectsEmptyString(): void
    {
        $this->expectException(InvalidHexValueException::class);

        ValueAssert::hexValue('');
    }
}
