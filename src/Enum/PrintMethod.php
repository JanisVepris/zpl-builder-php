<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum PrintMethod: string
{
    case DirectThermal = 'D';
    case ThermalTransfer = 'T';
}
