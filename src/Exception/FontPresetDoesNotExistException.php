<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;

class FontPresetDoesNotExistException extends InvalidArgumentException
{
    public function __construct(string $presetName)
    {
        parent::__construct(
            sprintf('Font preset "%s" does not exist.', $presetName),
        );
    }
}
