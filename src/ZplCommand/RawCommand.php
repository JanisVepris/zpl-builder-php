<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class RawCommand implements ZplCommand
{
    /** @throws StringLengthOutOfRangeException */
    public function __construct(
        private string $zpl,
    ) {
        ValueAssert::stringLengthBytes($zpl, 1, PHP_INT_MAX);
    }

    public function __toString()
    {
        return $this->zpl;
    }
}
