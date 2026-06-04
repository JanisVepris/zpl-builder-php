<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ScalableBitmappedFont implements ZplCommand
{
    public const string COMMAND = '^A';
    public const string FORMAT = '%s%s,%d,%d';

    /** Minimum character height/width in dots accepted by the printer for scalable fonts. */
    public const int MIN_DIMENSION = 10;

    private int $height;
    private int $width;

    /** @throws IntegerValueOutOfRangeException */
    public function __construct(
        private Font $font,
        private Orientation $orientation,
        int $height,
        int $width,
    ) {
        ValueAssert::int($height, self::MIN_DIMENSION);
        ValueAssert::int($width, self::MIN_DIMENSION);

        $this->height = $height;
        $this->width = $width;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->font->value,
            $this->orientation->value,
            $this->height,
            $this->width,
        );
    }
}
