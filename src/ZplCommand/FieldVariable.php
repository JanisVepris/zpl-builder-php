<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldVariable implements ZplCommand
{
    public const string COMMAND = '^FV';
    public const string FORMAT = '%s';

    /** Printer command-text buffer limit (bytes) for `^FV` field data. */
    public const int MAX_DATA_BYTES = 3072;

    private string $data;

    public function __construct(
        string $data,
    ) {
        ValueAssert::stringLengthBytes($data, 0, self::MAX_DATA_BYTES);
        ValueAssert::stringNotContains($data);

        $this->data = $data;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->data);
    }
}
