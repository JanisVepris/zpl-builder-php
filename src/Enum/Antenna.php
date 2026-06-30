<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Antenna: string
{
    case Diversity = 'D';
    case Left = 'L';
    case Right = 'R';
}
