<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class CalibrateRfidTransponder implements ZplCommand
{
    public const string COMMAND = '^HR';
    public const string FORMAT = '%s,%s';

    /** Largest byte length the spec accepts for the start/end strings (fewer than 65 characters). */
    public const int MAX_STRING_BYTES = 64;

    private string $endString;
    private string $startString;

    public function __construct(
        string $startString,
        string $endString,
    ) {
        ValueAssert::stringLengthBytes($startString, 1, self::MAX_STRING_BYTES);
        ValueAssert::stringNotContains($startString, ['^', '~', ',']);
        ValueAssert::stringLengthBytes($endString, 1, self::MAX_STRING_BYTES);
        ValueAssert::stringNotContains($endString, ['^', '~', ',']);

        $this->startString = $startString;
        $this->endString = $endString;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->startString,
            $this->endString,
        );
    }
}
