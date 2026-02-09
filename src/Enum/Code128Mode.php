<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Code128Mode: string
{
    case No_mode = 'N';
    case UCC_case = 'U';
    case AUTO = 'A';
    case UCC_EAN = 'D';
}
