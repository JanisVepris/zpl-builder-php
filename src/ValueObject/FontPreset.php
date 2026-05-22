<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ValueObject;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;

readonly class FontPreset
{
    /** @throws IntegerValueOutOfRangeException */
    public function __construct(
        public Font $font,
        public int $height,
        public int $width,
    ) {
        ValueAssert::int($height);
        ValueAssert::int($width);
    }
}
