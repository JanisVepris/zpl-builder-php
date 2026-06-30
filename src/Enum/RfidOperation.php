<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidOperation: string
{
    case Read = 'R';
    case ReadPassword = 'P';
    case Write = 'W';
    case WriteWithLock = 'L';
}
