<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class InvalidFieldCommentException extends InvalidArgumentException
{
    #[Pure]
    public function __construct(string $value)
    {
        parent::__construct(
            message: sprintf(
                'Invalid field comment: %s. The characters ^ and ~ are not allowed because they terminate the comment.',
                $value,
            ),
        );
    }
}
