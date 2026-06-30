<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintMethod;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class MediaType implements ZplCommand
{
    public const string COMMAND = '^MT';
    public const string FORMAT = '%s';

    public function __construct(
        private PrintMethod $method,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->method->value);
    }
}
