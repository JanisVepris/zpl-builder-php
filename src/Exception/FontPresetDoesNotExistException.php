<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

final class FontPresetDoesNotExistException extends InvalidArgumentException
{
    #[Pure]
    public function __construct(string $presetName)
    {
        parent::__construct(
            sprintf('Font preset "%s" does not exist.', $presetName),
        );
    }
}
