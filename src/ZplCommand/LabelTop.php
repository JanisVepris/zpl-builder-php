<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class LabelTop implements ZplCommand
{
    public const string COMMAND = '^LT';
    public const string FORMAT = '%d';

    /** Largest absolute label-top offset the printer accepts (in dot rows). */
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
