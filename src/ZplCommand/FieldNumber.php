<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldNumber implements ZplCommand
{
    public const string COMMAND = '^FN';
    public const string FORMAT = '%d';

    private int $number;

    public function __construct(
        int $number,
    ) {
        ValueAssert::int($number, 0, 9999);

        $this->number = $number;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->number);
    }
}
