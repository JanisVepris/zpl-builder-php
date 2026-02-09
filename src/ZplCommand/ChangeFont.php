<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

class ChangeFont implements ZplCommand
{
    private const string FORMAT = '^CF%s,%d,%d';

    public function __construct(
        private readonly int|string $font,
        private readonly int $height,
        private readonly int $width,
    ) {}

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->font,
            $this->height,
            $this->width,
        );
    }
}
