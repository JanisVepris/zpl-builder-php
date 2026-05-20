<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum StorageDevice: string
{
    case Ram = 'R';
    case Flash = 'E';
    case MemoryCardA = 'A';
    case MemoryCardB = 'B';
}
