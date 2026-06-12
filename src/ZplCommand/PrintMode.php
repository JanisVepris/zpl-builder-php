<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PostPrintAction;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintMode implements ZplCommand
{
    public const string COMMAND = '^MM';
    public const string FORMAT = '%s,%s';

    public function __construct(
        private PostPrintAction $mode,
        private bool $prepeel,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->mode->value,
            BoolToStr::conv($this->prepeel),
        );
    }
}
