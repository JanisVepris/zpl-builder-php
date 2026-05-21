<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class FieldOrigin implements ZplCommand
{
    private const string FORMAT = '^FO%d,%d';
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
        return sprintf(self::FORMAT, $this->x, $this->y);
    }
}
