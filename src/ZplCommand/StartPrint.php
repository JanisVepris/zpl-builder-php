<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class StartPrint implements ZplCommand
{
    public const string COMMAND = '^SP';
    public const string FORMAT = '%d';

    private int $dotRow;

    public function __construct(
        int $dotRow,
    ) {
        ValueAssert::int($dotRow);

        $this->dotRow = $dotRow;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->dotRow);
    }
}
