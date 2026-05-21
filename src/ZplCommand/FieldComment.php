<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class FieldComment implements ZplCommand
{
    private const string FORMAT = '^FX%s';

    private string $text;

    public function __construct(
        string $text,
    ) {
        ValueAssert::stringLength($text, 0, 3072);
        ValueAssert::stringNotContains($text);

        $this->text = $text;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->text);
    }
}
