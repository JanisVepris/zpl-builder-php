<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Antenna;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetAntennaParameters implements ZplCommand
{
    public const string COMMAND = '^WA';
    public const string FORMAT = '%s,%s';

    public function __construct(
        private Antenna $receive,
        private Antenna $transmit,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->receive->value,
            $this->transmit->value,
        );
    }
}
