<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use JetBrains\PhpStorm\Pure;
use OutOfRangeException;

final class FloatValueOutOfRangeException extends OutOfRangeException
{
    #[Pure]
    public function __construct(float $value, float $min, float $max)
    {
        parent::__construct(
            sprintf('Float value %d is out of range. Expected between %d and %d.', $value, $min, $max),
        );
    }
}
