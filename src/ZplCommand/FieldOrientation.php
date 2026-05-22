<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldOrientation implements ZplCommand
{
    private const string FORMAT = '^FW%s';

    public function __construct(
        private Orientation $fieldRotation,
    ) {}

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->fieldRotation->value);
    }
}
