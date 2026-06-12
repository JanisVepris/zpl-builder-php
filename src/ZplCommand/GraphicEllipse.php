<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicEllipse implements ZplCommand
{
    public const string COMMAND = '^GE';
    public const string FORMAT = '%d,%d,%d,%s';

    /** Largest ellipse width / height / border thickness the printer accepts (in dots). */
    public const int MAX_SIZE = 4095;

    /** Smallest ellipse width / height the printer accepts (in dots). */
    public const int MIN_SIZE = 3;

    private LineColor $color;
    private int $height;
    private int $thickness;
    private int $width;

    public function __construct(
        int $width,
        int $height,
        int $thickness,
        LineColor $color,
    ) {
        ValueAssert::int($width, self::MIN_SIZE, self::MAX_SIZE);
        ValueAssert::int($height, self::MIN_SIZE, self::MAX_SIZE);
        // The spec lists 2..4095 for thickness but documents 1 as the value used when
        // the parameter is omitted, so the lower bound is relaxed to 1 to keep that
        // default expressible (matching how `^GB` accepts a thickness of 1).
        ValueAssert::int($thickness, 1, self::MAX_SIZE);

        $this->width = $width;
        $this->height = $height;
        $this->thickness = $thickness;
        $this->color = $color;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->width,
            $this->height,
            $this->thickness,
            $this->color->value,
        );
    }
}
