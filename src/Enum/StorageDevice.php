<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum StorageDevice: string
{
    case Flash = 'E';
    case MemoryCardA = 'A';
    case MemoryCardB = 'B';
    case Ram = 'R';
}
