<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MediaTrackingType;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class MediaTracking implements ZplCommand
{
    public const string COMMAND = '^MN';
    public const string FORMAT = '%s';

    public function __construct(
        private MediaTrackingType $tracking,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->tracking->value);
    }
}
