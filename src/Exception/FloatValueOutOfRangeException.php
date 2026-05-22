<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use RangeException;

final class FloatValueOutOfRangeException extends RangeException
{
    public function __construct(float $value, float $min, float $max)
    {
        parent::__construct(
            sprintf('Float value %g is out of range. Expected between %g and %g.', $value, $min, $max),
        );
    }
}
