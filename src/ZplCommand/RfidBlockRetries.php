<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class RfidBlockRetries implements ZplCommand
{
    public const string COMMAND = '^RR';
    public const string FORMAT = '%d';

    /** Highest number of block read/write retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private int $retries;

    public function __construct(
        int $retries,
    ) {
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->retries = $retries;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->retries);
    }
}
