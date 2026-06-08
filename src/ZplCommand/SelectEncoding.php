<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SelectEncoding implements ZplCommand
{
    public const string COMMAND = '^SE';
    public const string EXTENSION = 'DAT';
    public const string FORMAT = '%s:%s.%s';
    public const int MAX_NAME_BYTES = 8;

    private StorageDevice $device;
    private string $name;

    public function __construct(
        StorageDevice $device,
        string $name,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);

        $this->device = $device;
        $this->name = $name;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->device->value,
            $this->name,
            self::EXTENSION,
        );
    }
}
