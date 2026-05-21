<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Util;

class FieldDataEncoder
{
    public static function escape(string $raw, string $indicator = '_'): string
    {
        $map = [
            $indicator => $indicator.sprintf('%02X', ord($indicator)),
            '^' => $indicator.'5E',
            '~' => $indicator.'7E',
        ];

        return strtr($raw, $map);
    }
}
