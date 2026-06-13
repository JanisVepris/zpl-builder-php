<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum WepKeyStorage: string
{
    case Hex = 'H';
    case StringValue = 'S';
}
