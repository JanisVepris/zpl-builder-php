<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SerializationField implements ZplCommand
{
    public const string COMMAND = '^SF';
    public const string FORMAT = '%s,%s';

    /** Combined printer command-text buffer limit (bytes) for the `^SF` mask + increment (3K). */
    public const int MAX_COMBINED_BYTES = 3072;
    private string $increment;

    private string $mask;

    public function __construct(
        string $mask,
        string $increment,
    ) {
        ValueAssert::stringLengthBytes($mask, 1, self::MAX_COMBINED_BYTES);
        ValueAssert::stringNotContains($mask, ['^', '~', ',']);

        ValueAssert::stringLengthBytes($increment, 1, self::MAX_COMBINED_BYTES);
        ValueAssert::stringNotContains($increment, ['^', '~', ',']);

        ValueAssert::stringLengthBytes($mask . $increment, 2, self::MAX_COMBINED_BYTES);

        $this->mask = $mask;
        $this->increment = $increment;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->mask, $this->increment);
    }
}
