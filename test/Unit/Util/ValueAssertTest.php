<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Util;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\InvalidHexValueException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ValueAssert::class)]
class ValueAssertTest extends UnitTestCase
{
    public function testEmptyStringPassesWithZeroMin(): void
    {
        ValueAssert::stringLengthBytes('', 0, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testFloatAboveMaxThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        ValueAssert::float(32000.1);
    }

    public function testFloatBelowMinThrows(): void
    {
        $this->expectException(FloatValueOutOfRangeException::class);

        ValueAssert::float(-0.1);
    }

    public function testFloatExceptionMessageRendersFloatValueNotInteger(): void
    {
        try {
            ValueAssert::float(-0.5);
            self::fail('Expected exception was not thrown');
        } catch (FloatValueOutOfRangeException $e) {
            self::assertStringContainsString('-0.5', $e->getMessage());
        }
    }

    public function testFloatRespectsCustomRange(): void
    {
        ValueAssert::float(2.5, 2.0, 3.0);

        $this->expectNotToPerformAssertions();
    }

    public function testFloatWithinDefaultRangePasses(): void
    {
        ValueAssert::float(123.45);

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

    public function testHexValueRejectsEmptyString(): void
    {
        $this->expectException(InvalidHexValueException::class);

        ValueAssert::hexValue('');
    }

    public function testHexValueRejectsNonHexCharacters(): void
    {
        $this->expectException(InvalidHexValueException::class);

        ValueAssert::hexValue('g0');
    }

    public function testIntAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        ValueAssert::int(32001);
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

    public function testIntOutsideCustomRangeThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        ValueAssert::int(11, 1, 10);
    }

    public function testIntRespectsCustomRange(): void
    {
        ValueAssert::int(5, 1, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testIntWithinDefaultRangePasses(): void
    {
        ValueAssert::int(100);

        $this->expectNotToPerformAssertions();
    }

    public function testStringLengthMeasuresBytesNotCharacters(): void
    {
        // 'héllo' is 6 bytes in UTF-8 (é is encoded as 0xC3 0xA9), not 5 characters.
        ValueAssert::stringLengthBytes('héllo', 6, 6);

        $this->expectNotToPerformAssertions();
    }

    public function testStringLengthRejectsMultiByteStringExceedingByteLimit(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        // 'héllo' is 6 bytes; a max of 5 bytes must reject it.
        ValueAssert::stringLengthBytes('héllo', 0, 5);
    }

    public function testStringLengthWithinRangePasses(): void
    {
        ValueAssert::stringLengthBytes('hello', 1, 10);

        $this->expectNotToPerformAssertions();
    }

    public function testStringNotContainsAllowsCleanString(): void
    {
        ValueAssert::stringNotContains('Hello World');

        $this->expectNotToPerformAssertions();
    }

    public function testStringNotContainsAllowsEmptyForbiddenList(): void
    {
        ValueAssert::stringNotContains('Anything ^ ~ goes', []);

        $this->expectNotToPerformAssertions();
    }

    public function testStringNotContainsAllowsEmptyString(): void
    {
        ValueAssert::stringNotContains('');

        $this->expectNotToPerformAssertions();
    }

    public function testStringNotContainsExceptionMessageNamesMatchedSubstring(): void
    {
        try {
            ValueAssert::stringNotContains('Hello ~JS');
            self::fail('Expected exception was not thrown');
        } catch (StringValueContainsBannedValuesException $e) {
            self::assertStringContainsString('~', $e->getMessage());
        }
    }

    public function testStringNotContainsRespectsCustomForbiddenList(): void
    {
        ValueAssert::stringNotContains('Hello ^ World', ['~']);

        $this->expectNotToPerformAssertions();
    }

    public function testStringNotContainsThrowsOnCustomSubstring(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        ValueAssert::stringNotContains('please do not include the word banana here', ['banana']);
    }

    public function testStringNotContainsThrowsOnDefaultCaret(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        ValueAssert::stringNotContains('Hello ^XA');
    }

    public function testStringNotContainsThrowsOnDefaultTilde(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        ValueAssert::stringNotContains('Hello ~JS');
    }

    public function testStringTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        ValueAssert::stringLengthBytes('hello world', 1, 5);
    }

    public function testStringTooShortThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        ValueAssert::stringLengthBytes('hi', 3, 10);
    }
}
