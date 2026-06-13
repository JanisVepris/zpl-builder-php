<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidPowerLevel: string
{
    case High = 'H';
    case Low = 'L';
    case Medium = 'M';
}
