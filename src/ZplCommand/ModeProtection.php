<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ProtectedMode;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ModeProtection implements ZplCommand
{
    public const string COMMAND = '^MP';
    public const string FORMAT = '%s';

    public function __construct(
        private ProtectedMode $mode,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->mode->value);
    }
}
