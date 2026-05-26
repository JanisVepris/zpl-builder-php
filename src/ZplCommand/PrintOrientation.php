<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintOrientation implements ZplCommand
{
    public const string COMMAND = '^PO';
    public const string FORMAT = '%s';

    public function __construct(
        private LabelFlip $orientation,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->orientation->value);
    }
}
