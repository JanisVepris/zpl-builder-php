<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum CacheType: string
{
    case Internal = '1';
    case Normal = '0';
}
