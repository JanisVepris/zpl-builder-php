<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class LabelLength implements ZplCommand
{
    public const string COMMAND = '^LL';
    public const string FORMAT = '%d';
    private int $length;

    public function __construct(
        int $length,
    ) {
        ValueAssert::int($length, 1);
        $this->length = $length;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->length);
    }
}
