<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Util;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\InvalidHexValueException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;

class ValueAssert
{
    /** @throws IntegerValueOutOfRangeException */
    public static function int(int $value, int $min = 0, int $max = 32000): void
    {
        if ($value < $min || $value > $max) {
            throw new IntegerValueOutOfRangeException(
                value: $value,
                min: $min,
                max: $max,
            );
        }
    }

    /** @throws FloatValueOutOfRangeException */
    public static function float(float $value, float $min = 0.0, float $max = 32000.0): void
    {
        if ($value < $min || $value > $max) {
            throw new FloatValueOutOfRangeException(
                value: $value,
                min: $min,
                max: $max,
            );
        }
    }

    /** @throws StringLengthOutOfRangeException */
    public static function stringLength(string $string, int $minBytes, int $maxBytes): void
    {
        $length = mb_strlen($string);
        if ($length < $minBytes || $length > $maxBytes) {
            throw new StringLengthOutOfRangeException(
                length: $length,
                min: $minBytes,
                max: $maxBytes,
            );
        }
    }

    public static function hexValue(string $value): void
    {
        if (!ctype_xdigit($value)) {
            throw new InvalidHexValueException($value);
        }
    }

    /**
     * @param array<int, string> $forbiddenSubstrings
     *
     * @throws StringValueContainsBannedValuesException
     */
    public static function stringNotContains(string $string, array $forbiddenSubstrings = ['^', '~']): void
    {
        foreach ($forbiddenSubstrings as $substring) {
            if (str_contains($string, $substring)) {
                throw new StringValueContainsBannedValuesException(
                    value: $string,
                    forbiddenSubstring: $substring,
                );
            }
        }
    }
}
