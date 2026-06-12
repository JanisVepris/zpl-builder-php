<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WiredPrintServerCheck;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SearchWiredPrintServer implements ZplCommand
{
    public const string COMMAND = '^NB';
    public const string FORMAT = '%s';

    public function __construct(
        private WiredPrintServerCheck $check,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->check->value);
    }
}
