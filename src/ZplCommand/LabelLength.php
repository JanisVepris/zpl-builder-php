<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

class LabelLength implements ZplCommand
{
    private const string FORMAT = '^LL%d';
    private readonly int $length;

    public function __construct(
        int $length,
    ) {
        ValueAssert::int($length, 1);
        $this->length = $length;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->length);
    }
}
