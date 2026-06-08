<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;

class ConflictingClockModeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(
            'The ^SL mode slot accepts either a ClockMode or a numeric tolerance, not both; provide only one.',
        );
    }
}
