<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

class EndFormat implements ZplCommand
{
    private const string FORMAT = '^XZ';

    public function __toString()
    {
        return self::FORMAT;
    }
}
