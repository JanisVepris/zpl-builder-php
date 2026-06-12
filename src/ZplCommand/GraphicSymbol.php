<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicSymbol implements ZplCommand
{
    public const string COMMAND = '^GS';
    public const string FORMAT = '%s,%d,%d';

    private int $height;
    private Orientation $orientation;
    private int $width;

    /**
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(
        Orientation $orientation,
        int $height,
        int $width,
    ) {
        ValueAssert::int($height);
        ValueAssert::int($width);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->width = $width;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            $this->width,
        );
    }
}
