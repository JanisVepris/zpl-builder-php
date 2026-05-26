<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldSeparator implements ZplCommand
{
    public const string COMMAND = '^FS';
    public const string FORMAT = '';

    public function __toString()
    {
        return self::COMMAND;
    }
}
