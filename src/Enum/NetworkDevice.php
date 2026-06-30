<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum NetworkDevice: string
{
    case Printer = 'P';
    case PrintServer = 'M';
}
