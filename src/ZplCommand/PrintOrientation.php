<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintOrientation as PrintOrientationEnum;
use Janisvepris\ZplBuilder\ZplCommand;

class PrintOrientation implements ZplCommand
{
    private const string FORMAT = '^PO%s';

    public function __construct(
        private readonly PrintOrientationEnum $orientation,
    ) {}

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->orientation->value);
    }
}
