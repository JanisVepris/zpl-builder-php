<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeTlc39 implements ZplCommand
{
    public const string COMMAND = '^BT';
    public const string FORMAT = '%s,%d,%0.1F,%d,%d,%d';

    public const int MAX_CODE39_HEIGHT = 9999;
    public const int MAX_MICRO_PDF_ROW_HEIGHT = 255;
    public const float MAX_RATIO = 3.0;
    public const int MAX_WIDTH = 10;
    public const float MIN_RATIO = 2.0;

    private int $code39Height;
    private int $code39Width;
    private int $microPdfRowHeight;
    private int $microPdfWidth;
    private Orientation $orientation;
    private float $wideToNarrowRatio;

    public function __construct(
        Orientation $orientation,
        int $code39Width,
        float $wideToNarrowRatio,
        int $code39Height,
        int $microPdfWidth,
        int $microPdfRowHeight,
    ) {
        ValueAssert::int($code39Width, 1, self::MAX_WIDTH);
        ValueAssert::float($wideToNarrowRatio, self::MIN_RATIO, self::MAX_RATIO);
        ValueAssert::int($code39Height, 1, self::MAX_CODE39_HEIGHT);
        ValueAssert::int($microPdfWidth, 1, self::MAX_WIDTH);
        ValueAssert::int($microPdfRowHeight, 1, self::MAX_MICRO_PDF_ROW_HEIGHT);

        $this->orientation = $orientation;
        $this->code39Width = $code39Width;
        $this->wideToNarrowRatio = $wideToNarrowRatio;
        $this->code39Height = $code39Height;
        $this->microPdfWidth = $microPdfWidth;
        $this->microPdfRowHeight = $microPdfRowHeight;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->code39Width,
            $this->wideToNarrowRatio,
            $this->code39Height,
            $this->microPdfWidth,
            $this->microPdfRowHeight,
        );
    }
}
