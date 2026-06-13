<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum WepEncryptionMode: string
{
    case Bit128 = '128';
    case Bit40 = '40';
    case Off = 'OFF';
}
