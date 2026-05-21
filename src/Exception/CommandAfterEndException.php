<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Exception;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

final class CommandAfterEndException extends RuntimeException
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Adding ZPL commands after a format has ended is not allowed');
    }
}
