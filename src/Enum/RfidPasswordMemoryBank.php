<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidPasswordMemoryBank: string
{
    case Access = 'A';
    case Epc = 'E';
    case Kill = 'K';
    case TagIdentifier = 'T';
    case User = 'U';
}
