<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DiagonalOrientation;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicDiagonalLine implements ZplCommand
{
    public const string COMMAND = '^GD';
    public const string FORMAT = '%d,%d,%d,%s,%s';

    /** Smallest bounding-box width / height the printer accepts (in dots). */
    public const int MIN_SIZE = 3;

    private LineColor $color;
    private int $height;
    private DiagonalOrientation $orientation;
    private int $thickness;
    private int $width;

    public function __construct(
        int $width,
        int $height,
        int $thickness,
        LineColor $color,
        DiagonalOrientation $orientation,
    ) {
        ValueAssert::int($width, self::MIN_SIZE);
        ValueAssert::int($height, self::MIN_SIZE);
        ValueAssert::int($thickness, 1);

        $this->width = $width;
        $this->height = $height;
        $this->thickness = $thickness;
        $this->color = $color;
        $this->orientation = $orientation;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->width,
            $this->height,
            $this->thickness,
            $this->color->value,
            $this->orientation->value,
        );
    }
}
