<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ZplMode;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetZpl implements ZplCommand
{
    public const string COMMAND = '^SZ';
    public const string FORMAT = '%s';

    public function __construct(
        private ZplMode $mode,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->mode->value);
    }
}
