<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintDirection;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldParameter implements ZplCommand
{
    public const string COMMAND = '^FP';
    public const string FORMAT = '%s,%d';

    /** Maximum additional inter-character gap (in dots) per the ZPL spec. */
    public const int MAX_GAP = 9999;
    private PrintDirection $direction;
    private int $gap;

    public function __construct(
        PrintDirection $direction,
        int $gap,
    ) {
        ValueAssert::int($gap, 0, self::MAX_GAP);

        $this->direction = $direction;
        $this->gap = $gap;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->direction->value,
            $this->gap,
        );
    }
}
