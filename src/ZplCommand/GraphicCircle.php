<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicCircle implements ZplCommand
{
    public const string COMMAND = '^GC';
    public const string FORMAT = '%d,%d,%s';

    /** Largest circle diameter / border thickness the printer accepts (in dots). */
    public const int MAX_SIZE = 4095;

    /** Smallest circle diameter the printer accepts (in dots). */
    public const int MIN_DIAMETER = 3;

    private LineColor $color;
    private int $diameter;
    private int $thickness;

    public function __construct(
        int $diameter,
        int $thickness,
        LineColor $color,
    ) {
        ValueAssert::int($diameter, self::MIN_DIAMETER, self::MAX_SIZE);
        // The spec lists 2..4095 for thickness but documents 1 as the value used when
        // the parameter is omitted, so the lower bound is relaxed to 1 to keep that
        // default expressible (matching how `^GB` accepts a thickness of 1).
        ValueAssert::int($thickness, 1, self::MAX_SIZE);

        $this->diameter = $diameter;
        $this->thickness = $thickness;
        $this->color = $color;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->diameter,
            $this->thickness,
            $this->color->value,
        );
    }
}
