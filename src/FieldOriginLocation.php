<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Stringable;

readonly class FieldOriginLocation implements Stringable
{
    public bool $excluded;
    public int $x;
    public int $y;

    /** @throws IntegerValueOutOfRangeException */
    public function __construct(
        int $x = 0,
        int $y = 0,
        bool $excluded = false,
    ) {
        ValueAssert::int($x);
        ValueAssert::int($y);

        $this->x = $x;
        $this->y = $y;
        $this->excluded = $excluded;
    }

    public function __toString(): string
    {
        if ($this->excluded) {
            return 'e,e';
        }

        return sprintf('%d,%d', $this->x, $this->y);
    }

    /** @throws IntegerValueOutOfRangeException */
    public static function at(int $x, int $y): self
    {
        return new self($x, $y);
    }

    public static function excluded(): self
    {
        return new self(excluded: true);
    }
}
