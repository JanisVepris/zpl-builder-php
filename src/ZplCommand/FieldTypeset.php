<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldTypeset implements ZplCommand
{
    public const string COMMAND = '^FT';
    public const string FORMAT = '%d,%d';
    private int $x;
    private int $y;

    public function __construct(
        int $x,
        int $y,
    ) {
        ValueAssert::int($x);
        ValueAssert::int($y);

        $this->y = $y;
        $this->x = $x;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->x, $this->y);
    }
}
