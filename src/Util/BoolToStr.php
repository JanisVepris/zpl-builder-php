<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Util;

class BoolToStr
{
    public static function conv(bool $value): string
    {
        return $value ? 'Y' : 'N';
    }
}
