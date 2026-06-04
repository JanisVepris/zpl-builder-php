<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum PrintDirection: string
{
    case Horizontal = 'H';
    case Reverse = 'R';
    case Vertical = 'V';
}
