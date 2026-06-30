<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidMotion: string
{
    case Feed = '0';
    case NoFeed = '1';
}
