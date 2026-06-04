<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldReversePrint implements ZplCommand
{
    public const string COMMAND = '^FR';
    public const string FORMAT = '';

    public function __toString()
    {
        return self::COMMAND;
    }
}
