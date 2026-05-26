<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;

class TertiaryClockIndicatorWithoutSecondaryException extends InvalidArgumentException
{
    public function __construct(string $tertiary)
    {
        parent::__construct(
            sprintf(
                'Cannot set tertiary clock indicator "%s" without also providing a secondary indicator; ^FC parameters are positional.',
                $tertiary,
            ),
        );
    }
}
