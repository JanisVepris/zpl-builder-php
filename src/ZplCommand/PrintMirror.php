<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintMirror implements ZplCommand
{
    public const string COMMAND = '^PM';
    public const string FORMAT = '%s';

    public function __construct(
        private bool $mirror,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, BoolToStr::conv($this->mirror));
    }
}
