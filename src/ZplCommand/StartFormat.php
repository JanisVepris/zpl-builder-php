<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class StartFormat implements ZplCommand
{
    private const string FORMAT = '^XA';

    public function __toString()
    {
        return self::FORMAT;
    }
}
