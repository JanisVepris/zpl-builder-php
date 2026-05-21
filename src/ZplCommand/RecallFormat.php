<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class RecallFormat implements ZplCommand
{
    private const string FORMAT = '^XF%s:%s.%s';
    private StorageDevice $device;
    private string $extension;
    private string $name;

    public function __construct(
        StorageDevice $device,
        string $name,
        string $extension,
    ) {
        ValueAssert::stringLengthBytes($name, 1, 16);
        ValueAssert::stringLengthBytes($extension, 1, 3);

        $this->device = $device;
        $this->name = $name;
        $this->extension = $extension;
    }

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->device->value,
            $this->name,
            $this->extension,
        );
    }
}
