<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class RecallGraphic implements ZplCommand
{
    public const string COMMAND = '^XG';
    public const string FORMAT = '%s:%s.%s,%d,%d';

    /** Maximum byte length of an object extension (e.g. `GRF`). */
    public const int MAX_EXTENSION_BYTES = 3;

    /** Maximum magnification factor accepted on each axis. */
    public const int MAX_MAGNIFICATION = 10;

    /** Maximum byte length of an object name. */
    public const int MAX_NAME_BYTES = 16;

    private StorageDevice $device;
    private string $extension;
    private int $magnificationX;
    private int $magnificationY;
    private string $name;

    /**
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function __construct(
        StorageDevice $device,
        string $name,
        string $extension,
        int $magnificationX,
        int $magnificationY,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringLengthBytes($extension, 1, self::MAX_EXTENSION_BYTES);
        ValueAssert::int($magnificationX, 1, self::MAX_MAGNIFICATION);
        ValueAssert::int($magnificationY, 1, self::MAX_MAGNIFICATION);

        $this->device = $device;
        $this->name = $name;
        $this->extension = $extension;
        $this->magnificationX = $magnificationX;
        $this->magnificationY = $magnificationY;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->device->value,
            $this->name,
            $this->extension,
            $this->magnificationX,
            $this->magnificationY,
        );
    }
}
