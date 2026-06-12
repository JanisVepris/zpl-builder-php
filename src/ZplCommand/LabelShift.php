<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class LabelShift implements ZplCommand
{
    public const string COMMAND = '^LS';
    public const string FORMAT = '%d';

    /** Largest absolute shift the printer accepts (in dots). */
    public const int MAX_SHIFT = 9999;

    private int $shift;

    public function __construct(
        int $shift,
    ) {
        ValueAssert::int($shift, -self::MAX_SHIFT, self::MAX_SHIFT);

        $this->shift = $shift;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->shift);
    }
}
