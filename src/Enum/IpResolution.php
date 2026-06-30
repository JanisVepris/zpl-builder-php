<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum IpResolution: string
{
    case All = 'A';
    case Bootp = 'B';
    case Dhcp = 'D';
    case DhcpAndBootp = 'C';
    case GleaningOnly = 'G';
    case Permanent = 'P';
    case Rarp = 'R';
}
