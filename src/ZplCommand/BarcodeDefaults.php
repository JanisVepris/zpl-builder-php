<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeDefaults implements ZplCommand
{
    public const string COMMAND = '^BY';
    public const string FORMAT = '%d,%0.1F,%d';
    private int $height;

    private int $moduleWidth;
    private float $wideToNarrowRatio;

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
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->moduleWidth,
            $this->wideToNarrowRatio,
            $this->height,
        );
    }
}
