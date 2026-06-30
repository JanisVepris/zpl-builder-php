<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum DirectoryDevice: string
{
    case Flash = 'E';
    case MemoryCardA = 'A';
    case MemoryCardB = 'B';
    case Ram = 'R';
    case Resident = 'Z';
}
