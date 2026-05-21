<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class FieldHexIndicator implements ZplCommand
{
    private const string FORMAT = '^FH%s';
    private string $indicator;

    public function __construct(
        string $indicator,
    ) {
        ValueAssert::stringLengthBytes($indicator, 1, 1);
        ValueAssert::stringNotContains($indicator);

        $this->indicator = $indicator;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->indicator);
    }
}
