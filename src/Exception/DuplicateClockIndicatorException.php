<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;

class DuplicateClockIndicatorException extends InvalidArgumentException
{
    public function __construct(string $indicator, string $position, string $conflictsWith)
    {
        parent::__construct(
            sprintf(
                'Clock indicator "%s" used for %s conflicts with the %s indicator; ^FC requires each indicator to be distinct.',
                $indicator,
                $position,
                $conflictsWith,
            ),
        );
    }
}
