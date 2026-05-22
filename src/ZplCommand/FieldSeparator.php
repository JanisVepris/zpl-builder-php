<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

class FieldSeparator implements ZplCommand
{
    private const string FORMAT = '^FS';

    public function __toString()
    {
        return self::FORMAT;
    }
}
