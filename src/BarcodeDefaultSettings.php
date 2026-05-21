<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Util\ValueAssert;

final class BarcodeDefaultSettings
{
    public function __construct(
        private int $moduleWidth = 2,
        private float $wideToNarrowRatio = 3.0,
        private int $height = 10,
    ) {}

    public function moduleWidth(): int
    {
        return $this->moduleWidth;
    }

    public function setModuleWidth(int $moduleWidth): void
    {
        ValueAssert::int($moduleWidth, 1, 10);
        $this->moduleWidth = $moduleWidth;
    }

    public function wideToNarrowRatio(): float
    {
        return $this->wideToNarrowRatio;
    }

    public function setWideToNarrowRatio(float $wideToNarrowRatio): void
    {
        ValueAssert::float($wideToNarrowRatio, 2.0, 3.0);
        $this->wideToNarrowRatio = $wideToNarrowRatio;
    }

    public function height(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        ValueAssert::int($height, 1);
        $this->height = $height;
    }
}
