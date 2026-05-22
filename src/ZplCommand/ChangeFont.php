<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class ChangeFont implements ZplCommand
{
    private const string FORMAT = '^CF%s,%d,%d';

    private int $height;
    private int $width;

    /** @throws IntegerValueOutOfRangeException */
    public function __construct(
        private Font $font,
        int $height,
        int $width,
    ) {
        ValueAssert::int($height);
        ValueAssert::int($width);

        $this->height = $height;
        $this->width = $width;
    }

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
