<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldNumber implements ZplCommand
{
    private const string FORMAT = '^FN%d';

    private int $number;

    public function __construct(
        int $number,
    ) {
        ValueAssert::int($number, 0, 9999);

        $this->number = $number;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->number);
    }
}
