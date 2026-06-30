<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MediaFeedAction;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class MediaFeed implements ZplCommand
{
    public const string COMMAND = '^MF';
    public const string FORMAT = '%s,%s';

    public function __construct(
        private MediaFeedAction $powerUp,
        private MediaFeedAction $headClose,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->powerUp->value,
            $this->headClose->value,
        );
    }
}
