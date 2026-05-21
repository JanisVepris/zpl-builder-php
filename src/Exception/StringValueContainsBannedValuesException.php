<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;

final class StringValueContainsBannedValuesException extends InvalidArgumentException
{
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
