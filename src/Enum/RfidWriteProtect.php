<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidWriteProtect: string
{
    case NotProtected = '0';
    case WriteProtected = '1';
}
