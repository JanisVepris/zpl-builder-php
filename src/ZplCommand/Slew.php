<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class Slew implements ZplCommand
{
    public const string COMMAND = '^PF';
    public const string FORMAT = '%d';

    private int $dotRows;

    public function __construct(
        int $dotRows,
    ) {
        ValueAssert::int($dotRows);

        $this->dotRows = $dotRows;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->dotRows);
    }
}
