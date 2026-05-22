<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Stringable;

readonly class CharacterRemap implements Stringable
{
    public int $destination;
    public int $source;

    public function __construct(
        int $source,
        int $destination,
    ) {
        ValueAssert::int($source, 0, 255);
        ValueAssert::int($destination, 0, 255);

        $this->destination = $destination;
        $this->source = $source;
    }

    public function __toString(): string
    {
        return sprintf('%d,%d', $this->source, $this->destination);
    }
}
