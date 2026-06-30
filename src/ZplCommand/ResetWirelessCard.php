<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class ResetWirelessCard implements ZplCommand
{
    public const string COMMAND = '~WR';
    public const string FORMAT = '';

    public function __toString()
    {
        return self::COMMAND;
    }
}
