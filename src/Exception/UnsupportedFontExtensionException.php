<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use InvalidArgumentException;
use Janisvepris\ZplBuilder\Enum\FontExtension;

class UnsupportedFontExtensionException extends InvalidArgumentException
{
    public function __construct(FontExtension $extension, string $command)
    {
        parent::__construct(
            sprintf(
                'Font extension ".%s" is not supported by the %s command.',
                $extension->value,
                $command,
            ),
        );
    }
}
