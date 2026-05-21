<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class ChangeFont implements ZplCommand
{
    private const string FORMAT = '^CF%s,%d,%d';

    public function __construct(
        private Font $font,
        private int $height,
        private int $width,
    ) {}

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->font->value,
            $this->height,
            $this->width,
        );
    }
}
