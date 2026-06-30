<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum WiredPrintServerCheck: string
{
    case Check = 'C';
    case Skip = 'S';
}
