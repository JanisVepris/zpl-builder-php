<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class TransferObject implements ZplCommand
{
    public const string COMMAND = '^TO';
    public const string FORMAT = '%s:%s.%s,%s:%s.%s';

    /**
     * Maximum byte length of an object extension (e.g. `GRF`, `FNT`, `TTF`).
     */
    public const int MAX_EXTENSION_BYTES = 3;

    /**
     * Maximum byte length of an object name. Object names follow the same
     * download-name convention as `~DG`/`^A@` (the destination name is capped at
     * 8 by the spec; the source may reference any existing object name).
     */
    public const int MAX_NAME_BYTES = 16;

    private string $destinationExtension;
    private string $destinationName;
    private string $sourceExtension;
    private string $sourceName;

    /**
     * The `*` wildcard is permitted in `$sourceName`, `$sourceExtension`,
     * `$destinationName`, and `$destinationExtension` (e.g. `LOGO*`, `*`) to copy
     * multiple objects, per the `^TO` spec. The banned-character set rejects the
     * `d:name.ext` separators (`^`, `~`, `:`, `.`, `,`) but intentionally allows `*`.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        private StorageDevice $sourceDevice,
        string $sourceName,
        string $sourceExtension,
        private StorageDevice $destinationDevice,
        string $destinationName,
        string $destinationExtension,
    ) {
        ValueAssert::stringLengthBytes($sourceName, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringNotContains($sourceName, ['^', '~', ':', '.', ',']);
        ValueAssert::stringLengthBytes($sourceExtension, 1, self::MAX_EXTENSION_BYTES);
        ValueAssert::stringNotContains($sourceExtension, ['^', '~', ':', '.', ',']);
        ValueAssert::stringLengthBytes($destinationName, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringNotContains($destinationName, ['^', '~', ':', '.', ',']);
        ValueAssert::stringLengthBytes($destinationExtension, 1, self::MAX_EXTENSION_BYTES);
        ValueAssert::stringNotContains($destinationExtension, ['^', '~', ':', '.', ',']);

        $this->sourceName = $sourceName;
        $this->sourceExtension = $sourceExtension;
        $this->destinationName = $destinationName;
        $this->destinationExtension = $destinationExtension;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->sourceDevice->value,
            $this->sourceName,
            $this->sourceExtension,
            $this->destinationDevice->value,
            $this->destinationName,
            $this->destinationExtension,
        );
    }
}
