<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;

final class FontSettings
{
    public function __construct(
        private int $height = 9,
        private int $width = 5,
    ) {}

    public function height(): int
    {
        return $this->height;
    }

    /** @throws IntegerValueOutOfRangeException */
    public function setHeight(int $height): void
    {
        ValueAssert::int($height);

        $this->height = $height;
    }

    /** @throws IntegerValueOutOfRangeException */
    public function setWidth(int $width): void
    {
        ValueAssert::int($width);

        $this->width = $width;
    }

    public function width(): int
    {
        return $this->width;
    }
}
