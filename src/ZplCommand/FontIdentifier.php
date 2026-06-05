<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FontIdentifier implements ZplCommand
{
    public const string COMMAND = '^CW';
    public const string FORMAT = '%s,%s:%s.%s';

    /** Maximum byte length of the downloaded font's file name. */
    public const int MAX_NAME_BYTES = 8;

    private string $name;

    /**
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        private Font $font,
        private StorageDevice $device,
        string $name,
        private FontExtension $extension,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringNotContains($name, ['^', '~', ':', '.', ',']);

        $this->name = $name;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->font->value,
            $this->device->value,
            $this->name,
            $this->extension->value,
        );
    }
}
