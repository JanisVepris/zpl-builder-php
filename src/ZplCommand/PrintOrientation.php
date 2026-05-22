<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintOrientation implements ZplCommand
{
    private const string FORMAT = '^PO%s';

    public function __construct(
        private LabelFlip $orientation,
    ) {}

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->orientation->value);
    }
}
