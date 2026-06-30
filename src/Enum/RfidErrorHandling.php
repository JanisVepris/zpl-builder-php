<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidErrorHandling: string
{
    case ErrorMode = 'E';
    case NoAction = 'N';
    case PauseMode = 'P';
}
