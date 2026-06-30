<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum MemoryLetter: string
{
    case Flash = 'E';
    case MemoryCardA = 'A';
    case MemoryCardB = 'B';
    case None = 'NONE';
    case Ram = 'R';
}
