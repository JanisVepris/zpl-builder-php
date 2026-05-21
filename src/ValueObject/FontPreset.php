<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ValueObject;

use Janisvepris\ZplBuilder\Enum\Font;

final readonly class FontPreset
{
    public function __construct(
        public Font $font,
        public int $height,
        public int $width,
    ) {}
}
