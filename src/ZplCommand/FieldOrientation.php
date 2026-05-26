<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldOrientation implements ZplCommand
{
    public const string COMMAND = '^FW';
    public const string FORMAT = '%s';

    public function __construct(
        private Orientation $orientation,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->orientation->value);
    }
}
