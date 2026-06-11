<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Code49InterpretationLine;
use Janisvepris\ZplBuilder\Enum\Code49Mode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeCode49 implements ZplCommand
{
    public const string COMMAND = '^B4';
    public const string FORMAT = '%s,%d,%s,%s';

    private int $height;
    private Code49InterpretationLine $interpretationLine;
    private Code49Mode $mode;
    private Orientation $orientation;

    public function __construct(
        Orientation $orientation,
        int $height,
        Code49InterpretationLine $interpretationLine,
        Code49Mode $mode,
    ) {
        ValueAssert::int($height, 1);

        $this->orientation = $orientation;
        $this->height = $height;
        $this->interpretationLine = $interpretationLine;
        $this->mode = $mode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            $this->interpretationLine->value,
            $this->mode->value,
        );
    }
}
