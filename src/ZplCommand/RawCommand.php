<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

readonly class RawCommand implements ZplCommand
{
    public function __construct(
        private string $zpl,
    ) {}

    public function __toString()
    {
        return $this->zpl;
    }
}
