<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class EnableRfidMotion implements ZplCommand
{
    public const string COMMAND = '^RM';
    public const string FORMAT = '%s';

    public function __construct(
        private bool $enabled,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, BoolToStr::conv($this->enabled));
    }
}
