<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidByteFormat: string
{
    case Ascii = '0';
    case Hexadecimal = '1';
}
