<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class StartFormat implements ZplCommand
{
    public const string COMMAND = '^XA';
    public const string FORMAT = '';

    public function __toString()
    {
        return self::COMMAND;
    }
}
