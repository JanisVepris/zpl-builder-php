<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class LabelReversePrint implements ZplCommand
{
    private const string FORMAT = '^LR%s';

    public function __construct(
        private bool $reversePrint,
    ) {}

    public function __toString()
    {
        return sprintf(self::FORMAT, BoolToStr::conv($this->reversePrint));
    }
}
