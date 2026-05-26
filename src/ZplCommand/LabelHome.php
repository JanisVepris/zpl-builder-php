<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class LabelHome implements ZplCommand
{
    public const string COMMAND = '^LH';
    public const string FORMAT = '%d,%d';
    private int $x;
    private int $y;

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
        return self::COMMAND . sprintf(self::FORMAT, $this->x, $this->y);
    }
}
