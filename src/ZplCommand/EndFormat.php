<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

final class EndFormat implements ZplCommand
{
    private const string COMMAND = '^XZ';

    public function __toString()
    {
        return self::COMMAND;
    }
}
