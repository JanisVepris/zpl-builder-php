<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeMicroPdf417 implements ZplCommand
{
    public const string COMMAND = '^BF';
    public const string FORMAT = '%s,%d,%d';

    public const int MAX_HEIGHT = 9999;
    public const int MAX_MODE = 33;

    private int $height;
    private int $mode;
    private Orientation $orientation;

    public function __construct(
        Orientation $orientation,
        int $height,
        int $mode,
    ) {
        ValueAssert::int($height, 1, self::MAX_HEIGHT);
        ValueAssert::int($mode, 0, self::MAX_MODE);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->mode = $mode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            $this->mode,
        );
    }
}
