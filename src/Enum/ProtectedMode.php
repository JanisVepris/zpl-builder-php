<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum ProtectedMode: string
{
    case DisableCalibration = 'C';
    case DisableCancel = 'X';
    case DisableDarkness = 'D';
    case DisableFeed = 'F';
    case DisableMenu = 'M';
    case DisablePause = 'W';
    case DisablePosition = 'P';
    case DisableSaves = 'S';
    case EnableAll = 'E';
}
