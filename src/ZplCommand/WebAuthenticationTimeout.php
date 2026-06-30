<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class WebAuthenticationTimeout implements ZplCommand
{
    public const string COMMAND = '^NW';
    public const string FORMAT = '%d';

    /** Maximum web-authentication timeout, in minutes. */
    public const int MAX_MINUTES = 255;

    private int $minutes;

    public function __construct(
        int $minutes,
    ) {
        ValueAssert::int($minutes, 0, self::MAX_MINUTES);

        $this->minutes = $minutes;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->minutes);
    }
}
