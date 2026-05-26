<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;

class BarcodeDefaultSettings
{
    private int $height;
    private int $moduleWidth;
    private float $wideToNarrowRatio;

    /**
     * @throws FloatValueOutOfRangeException
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(int $moduleWidth = 2, float $wideToNarrowRatio = 3.0, int $height = 10)
    {
        $this->setModuleWidth($moduleWidth);
        $this->setWideToNarrowRatio($wideToNarrowRatio);
        $this->setHeight($height);
    }

    public function height(): int
    {
        return $this->height;
    }

    public function moduleWidth(): int
    {
        return $this->moduleWidth;
    }

    public function setHeight(int $height): void
    {
        ValueAssert::int($height, 1);
        $this->height = $height;
    }

    public function setModuleWidth(int $moduleWidth): void
    {
        ValueAssert::int($moduleWidth, 1, 10);
        $this->moduleWidth = $moduleWidth;
    }

    public function setWideToNarrowRatio(float $wideToNarrowRatio): void
    {
        ValueAssert::float($wideToNarrowRatio, 2.0, 3.0);
        $this->wideToNarrowRatio = $wideToNarrowRatio;
    }

    public function wideToNarrowRatio(): float
    {
        return $this->wideToNarrowRatio;
    }
}
