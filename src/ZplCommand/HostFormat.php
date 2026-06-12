<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class HostFormat implements ZplCommand
{
    public const string COMMAND = '^HF';
    public const string FORMAT = '%s:%s.%s';

    /** Maximum byte length of a stored format extension (e.g. `ZPL`). */
    public const int MAX_EXTENSION_BYTES = 3;

    /** Maximum byte length of a stored format name. */
    public const int MAX_NAME_BYTES = 16;

    private StorageDevice $device;
    private string $extension;
    private string $name;

    /**
     * @throws StringLengthOutOfRangeException
     */
    public function __construct(
        StorageDevice $device,
        string $name,
        string $extension,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringLengthBytes($extension, 1, self::MAX_EXTENSION_BYTES);

        $this->device = $device;
        $this->name = $name;
        $this->extension = $extension;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->device->value,
            $this->name,
            $this->extension,
        );
    }
}
