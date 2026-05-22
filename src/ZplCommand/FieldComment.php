<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldComment implements ZplCommand
{
    /** Printer command-text buffer limit (bytes) for `^FX` comment text. */
    public const int MAX_TEXT_BYTES = 3072;

    private const string FORMAT = '^FX%s';

    private string $text;

    public function __construct(
        string $text,
    ) {
        ValueAssert::stringLengthBytes($text, 0, self::MAX_TEXT_BYTES);
        ValueAssert::stringNotContains($text);

        $this->text = $text;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->text);
    }
}
