<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidReadWriteFormat: string
{
    case Ascii = 'A';
    case Epc = 'E';
    case Hexadecimal = 'H';
}
