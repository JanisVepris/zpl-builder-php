<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintWidth implements ZplCommand
{
    private const string FORMAT = '^PW%d';
    private int $width;

    public function __construct(
        int $width,
    ) {
        ValueAssert::int($width, 2);
        $this->width = $width;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->width);
    }
}
