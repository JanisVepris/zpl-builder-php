<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DirectoryDevice;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrintDirectoryLabel implements ZplCommand
{
    public const string COMMAND = '^WD';
    public const string FORMAT = '%s:%s.%s';

    private DirectoryDevice $device;
    private string $extension;
    private string $name;

    public function __construct(
        DirectoryDevice $device,
        string $name,
        string $extension,
    ) {
        ValueAssert::stringNotContains($name, ['^', '~', ',']);
        ValueAssert::stringNotContains($extension, ['^', '~', ',']);

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
