<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum RfidLockStyle: string
{
    case Locked = 'L';
    case PermanentlyLocked = 'P';
    case PermanentlyUnlocked = 'O';
    case Unlocked = 'U';
    case WriteValue = 'W';
}
