<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidReportMode: string
{
    case Disable = 'D';
    case Enable = 'E';
}
