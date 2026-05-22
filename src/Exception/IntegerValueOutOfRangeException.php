<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use RangeException;

final class IntegerValueOutOfRangeException extends RangeException
{
    public function __construct(int $value, int $min, int $max)
    {
        parent::__construct(
            sprintf('Integer value %d is out of range. Expected between %d and %d.', $value, $min, $max),
        );
    }
}
