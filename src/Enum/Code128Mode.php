<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Code128Mode: string
{
    case None = 'N';
    case UccCase = 'U';
    case Auto = 'A';
    case UccEan = 'D';
}
