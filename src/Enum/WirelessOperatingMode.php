<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum WirelessOperatingMode: string
{
    case Adhoc = 'A';
    case Infrastructure = 'I';
}
