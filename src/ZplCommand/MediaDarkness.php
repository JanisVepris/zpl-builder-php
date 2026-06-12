<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class MediaDarkness implements ZplCommand
{
    public const string COMMAND = '^MD';
    public const string FORMAT = '%d';

    /** Largest darkness adjustment, relative to the current setting, the printer accepts. */
    public const int MAX_ADJUSTMENT = 30;

    private int $level;

    public function __construct(
        int $level,
    ) {
        ValueAssert::int($level, -self::MAX_ADJUSTMENT, self::MAX_ADJUSTMENT);

        $this->level = $level;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->level);
    }
}
