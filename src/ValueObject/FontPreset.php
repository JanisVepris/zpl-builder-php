<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ValueObject;

final readonly class FontPreset
{
    public function __construct(
        public int|string $font,
        public int $height,
        public int $width,
    ) {}
}
