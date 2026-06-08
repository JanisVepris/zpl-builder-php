<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DateTimeFormat;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SelectDateTimeFormat implements ZplCommand
{
    public const string COMMAND = '^KD';
    public const string FORMAT = '%s';

    public function __construct(
        private DateTimeFormat $format,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->format->value);
    }
}
