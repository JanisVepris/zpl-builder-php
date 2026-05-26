<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintQuantity implements ZplCommand
{
    public const string COMMAND = '^PQ';
    public const string FORMAT = '%d';
    private int $quantity;

    public function __construct(
        int $quantity,
    ) {
        ValueAssert::int($quantity, 1, 99999999);
        $this->quantity = $quantity;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->quantity);
    }
}
