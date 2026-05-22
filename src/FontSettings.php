<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;

final class FontSettings
{
    private int $height;
    private int $width;

    /** @throws IntegerValueOutOfRangeException */
    public function __construct(int $height = 9, int $width = 5)
    {
        $this->setHeight($height);
        $this->setWidth($width);
    }

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
