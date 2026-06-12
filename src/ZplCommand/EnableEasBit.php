<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class EnableEasBit implements ZplCommand
{
    public const string COMMAND = '^RE';
    public const string FORMAT = '%s,%d';

    /** Highest number of retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private bool $enabled;
    private int $retries;

    public function __construct(
        bool $enabled,
        int $retries,
    ) {
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->enabled = $enabled;
        $this->retries = $retries;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            BoolToStr::conv($this->enabled),
            $this->retries,
        );
    }
}
