<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use RangeException;

final class StringLengthOutOfRangeException extends RangeException
{
    public function __construct(int $length, int $min, int $max)
    {
        parent::__construct(
            sprintf('String length %d is out of range. Expected between %d and %d.', $length, $min, $max),
        );
    }
}
