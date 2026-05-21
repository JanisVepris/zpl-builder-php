<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class FieldData implements ZplCommand
{
    private const string FORMAT = '^FD%s';

    private string $data;

    public function __construct(
        string $data,
    ) {
        ValueAssert::stringLengthBytes($data, 0, 3072);
        ValueAssert::stringNotContains($data);

        $this->data = $data;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->data);
    }
}
