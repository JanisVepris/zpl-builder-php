<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class LabelReversePrint implements ZplCommand
{
    public const string COMMAND = '^LR';
    public const string FORMAT = '%s';

    public function __construct(
        private bool $reversePrint,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, BoolToStr::conv($this->reversePrint));
    }
}
