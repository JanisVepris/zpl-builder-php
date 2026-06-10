<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\RssSymbologyType;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeRss implements ZplCommand
{
    public const string COMMAND = '^BR';
    public const string FORMAT = '%s,%s,%d,%d,%d,%d';

    public const int MAX_MAGNIFICATION = 10;
    public const int MAX_SEGMENT_WIDTH = 22;
    public const int MAX_SEPARATOR_HEIGHT = 2;
    public const int MIN_SEGMENT_WIDTH = 2;
    public const int MIN_SEPARATOR_HEIGHT = 1;

    private int $barcodeHeight;
    private int $magnification;
    private Orientation $orientation;
    private int $segmentWidth;
    private int $separatorHeight;
    private RssSymbologyType $symbologyType;

    public function __construct(
        Orientation $orientation,
        RssSymbologyType $symbologyType,
        int $magnification,
        int $separatorHeight,
        int $barcodeHeight,
        int $segmentWidth,
    ) {
        ValueAssert::int($magnification, 1, self::MAX_MAGNIFICATION);
        ValueAssert::int($separatorHeight, self::MIN_SEPARATOR_HEIGHT, self::MAX_SEPARATOR_HEIGHT);
        ValueAssert::int($barcodeHeight, 1);
        ValueAssert::int($segmentWidth, self::MIN_SEGMENT_WIDTH, self::MAX_SEGMENT_WIDTH);

        $this->orientation = $orientation;
        $this->symbologyType = $symbologyType;
        $this->magnification = $magnification;
        $this->separatorHeight = $separatorHeight;
        $this->barcodeHeight = $barcodeHeight;
        $this->segmentWidth = $segmentWidth;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->symbologyType->value,
            $this->magnification,
            $this->separatorHeight,
            $this->barcodeHeight,
            $this->segmentWidth,
        );
    }
}
