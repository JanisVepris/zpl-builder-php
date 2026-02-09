<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

class FieldData implements ZplCommand
{
    private const string FORMAT = '^FD%s';

    private readonly string $data;

    public function __construct(
        string $data,
    ) {
        ValueAssert::stringLength($data, 0, 3072);

        $this->data = $data;
    }

    public function __toString()
    {
        return sprintf(self::FORMAT, $this->data);
    }
}
