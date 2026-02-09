<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

class BarcodeDefaults implements ZplCommand
{
    private const string FORMAT = '^BY%d,%0.1f,%d';

    private readonly int $moduleWidth;
    private readonly float $wideToNarrowRatio;
    private readonly int $height;

    public function __construct(
        int $moduleWidth,
        float $wideToNarrowRatio,
        int $height,
    ) {
        ValueAssert::int($moduleWidth, 1, 10);
        ValueAssert::float($wideToNarrowRatio, 2.0, 3.0);
        ValueAssert::int($height, 1);

        $this->height = $height;
        $this->wideToNarrowRatio = $wideToNarrowRatio;
        $this->moduleWidth = $moduleWidth;
    }

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->moduleWidth,
            $this->wideToNarrowRatio,
            $this->height,
        );
    }
}
