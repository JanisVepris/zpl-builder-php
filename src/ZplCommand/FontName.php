<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FontName implements ZplCommand
{
    public const string COMMAND = '^A@';
    public const string FORMAT = '%s,%d,%d,%s:%s.%s';

    /** Maximum byte length of the font file name (printer object-name buffer). */
    public const int MAX_NAME_BYTES = 16;

    private int $height;
    private string $name;
    private int $width;

    /**
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        private Orientation $orientation,
        int $height,
        int $width,
        private StorageDevice $device,
        string $name,
        private FontExtension $extension,
    ) {
        ValueAssert::int($height);
        ValueAssert::int($width);
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringNotContains($name, ['^', '~', ':', '.', ',']);

        $this->height = $height;
        $this->width = $width;
        $this->name = $name;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            $this->width,
            $this->device->value,
            $this->name,
            $this->extension->value,
        );
    }
}
