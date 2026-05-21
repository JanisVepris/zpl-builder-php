<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\ZplCommand;

final readonly class ChangeFont implements ZplCommand
{
    private const string FORMAT = '^CF%s,%d,%d';

    public function __construct(
        private int|string $font,
        private int $height,
        private int $width,
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
