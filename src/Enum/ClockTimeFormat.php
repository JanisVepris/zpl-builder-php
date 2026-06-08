<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum ClockTimeFormat: string
{
    case Am = 'A';
    case Military24Hour = 'M';
    case Pm = 'P';
}
