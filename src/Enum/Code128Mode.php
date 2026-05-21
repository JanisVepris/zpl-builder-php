<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Code128Mode: string
{
    case Auto = 'A';
    case None = 'N';
    case UccCase = 'U';
    case UccEan = 'D';
}
