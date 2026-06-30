<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class TearOffAdjust implements ZplCommand
{
    public const string COMMAND = '~TA';
    public const string FORMAT = '%03d';

    /** Largest absolute media rest-position adjustment (in dot rows) the printer accepts. */
    public const int MAX_OFFSET = 120;

    private int $dotRows;

    public function __construct(
        int $dotRows,
    ) {
        ValueAssert::int($dotRows, -self::MAX_OFFSET, self::MAX_OFFSET);

        $this->dotRows = $dotRows;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->dotRows);
    }
}
