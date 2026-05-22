<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Util;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;

class FieldDataEncoder
{
    /**
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public static function escape(string $raw, string $indicator = '_'): string
    {
        ValueAssert::stringLengthBytes($indicator, 1, 1);
        ValueAssert::stringNotContains($indicator);

        $map = [
            $indicator => $indicator . sprintf('%02X', ord($indicator)),
            '^' => $indicator . '5E',
            '~' => $indicator . '7E',
        ];

        return strtr($raw, $map);
    }
}
