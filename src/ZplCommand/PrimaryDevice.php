<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\NetworkDevice;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrimaryDevice implements ZplCommand
{
    public const string COMMAND = '^NP';
    public const string FORMAT = '%s';

    public function __construct(
        private NetworkDevice $device,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->device->value);
    }
}
