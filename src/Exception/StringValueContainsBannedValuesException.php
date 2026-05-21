<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class StringValueContainsBannedValuesException extends InvalidArgumentException
{
    #[Pure]
    public function __construct(string $value, string $forbiddenSubstring)
    {
        parent::__construct(
            sprintf(
                'String value "%s" contains forbidden substring: %s',
                $value,
                $forbiddenSubstring,
            ),
        );
    }
}
