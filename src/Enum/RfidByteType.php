<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidByteType: string
{
    case Afi = 'A';
    case Dsfid = 'D';
}
