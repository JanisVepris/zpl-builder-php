<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

class LabelHome implements ZplCommand
{
    private const string FORMAT = '^LH%d,%d';
    private readonly int $x;
    private readonly int $y;

    public function __construct(
        int $x,
        int $y,
    ) {
        ValueAssert::int($x);
        ValueAssert::int($y);

        $this->x = $x;
        $this->y = $y;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->x, $this->y);
    }
}
