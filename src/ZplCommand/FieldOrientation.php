<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\ZplCommand;

class FieldOrientation implements ZplCommand
{
    private const string COMMAND = '^FW%s';

    public function __construct(
        private readonly Orientation $fieldRotation,
    ) {}

    public function __toString()
    {
        return sprintf(self::COMMAND, $this->fieldRotation->value);
    }
}
