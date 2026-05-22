<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicBox implements ZplCommand
{
    private const string FORMAT = '^GB%d,%d,%d,%s,%d';
    private LineColor $color;
    private int $height;
    private int $rounding;
    private int $thickness;
    private int $width;

    public function __construct(
        int $width,
        int $height,
        int $thickness,
        LineColor $color,
        int $rounding,
    ) {
        ValueAssert::int($width);
        ValueAssert::int($height);
        ValueAssert::int($thickness, 1);
        ValueAssert::int($rounding, 0, 8);

        $this->width = $width;
        $this->height = $height;
        $this->thickness = $thickness;
        $this->color = $color;
        $this->rounding = $rounding;
    }

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->width,
            $this->height,
            $this->thickness,
            $this->color->value,
            $this->rounding,
        );
    }
}
