<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum ClockMode: string
{
    case StartTime = 'S';
    case TimeNow = 'T';
}
