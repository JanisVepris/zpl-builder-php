<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use JetBrains\PhpStorm\Pure;
use UnexpectedValueException;

final class InvalidHexValueException extends UnexpectedValueException
{
    #[Pure]
    public function __construct(string $value)
    {
        parent::__construct(
            message: sprintf(
                'Invalid hex value: %s. Allowed values are 0-9 and A-F (a-f).',
                $value,
            ),
        );
    }
}
