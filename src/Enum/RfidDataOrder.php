<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidDataOrder: string
{
    case Normal = '0';
    case Reversed = '1';
}
