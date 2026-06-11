<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeLogmars implements ZplCommand
{
    public const string COMMAND = '^BL';
    public const string FORMAT = '%s,%d,%s';

    private int $height;
    private bool $interpretationAboveCode;
    private Orientation $orientation;

    public function __construct(
        Orientation $orientation,
        int $height,
        bool $interpretationAboveCode,
    ) {
        ValueAssert::int($height, 1);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->interpretationAboveCode = $interpretationAboveCode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            BoolToStr::conv($this->interpretationAboveCode),
        );
    }
}
