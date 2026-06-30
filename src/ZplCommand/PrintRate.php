<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintSpeed;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintRate implements ZplCommand
{
    public const string COMMAND = '^PR';
    public const string FORMAT = '%s,%s,%s';

    public function __construct(
        private PrintSpeed $print,
        private PrintSpeed $slew,
        private PrintSpeed $backfeed,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->print->value,
            $this->slew->value,
            $this->backfeed->value,
        );
    }
}
