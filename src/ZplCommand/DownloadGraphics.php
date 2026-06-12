<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class DownloadGraphics implements ZplCommand
{
    public const string COMMAND = '~DG';
    public const string FORMAT = '%s:%s.%s,%d,%d,%s';

    /**
     * Upper bound applied to the byte-count parameters (`t`, `w`). The spec sets no maximum — the
     * true limit is the printer's available memory — so this is a generous library-imposed sanity
     * cap rather than a hardware limit.
     */
    public const int MAX_BYTES = 99999999;

    /** Maximum byte length of an object extension (e.g. `GRF`). */
    public const int MAX_EXTENSION_BYTES = 3;

    /** Maximum byte length of an object name. */
    public const int MAX_NAME_BYTES = 16;

    private int $bytesPerRow;
    private string $data;
    private StorageDevice $device;
    private string $extension;
    private string $name;
    private int $totalBytes;

    /**
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        StorageDevice $device,
        string $name,
        string $extension,
        int $totalBytes,
        int $bytesPerRow,
        string $data,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::stringLengthBytes($extension, 1, self::MAX_EXTENSION_BYTES);
        ValueAssert::int($totalBytes, 1, self::MAX_BYTES);
        ValueAssert::int($bytesPerRow, 1, self::MAX_BYTES);
        // A caret or tilde in the data prematurely aborts the printer's download, so reject them.
        ValueAssert::stringNotContains($data);

        $this->device = $device;
        $this->name = $name;
        $this->extension = $extension;
        $this->totalBytes = $totalBytes;
        $this->bytesPerRow = $bytesPerRow;
        $this->data = $data;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->device->value,
            $this->name,
            $this->extension,
            $this->totalBytes,
            $this->bytesPerRow,
            $this->data,
        );
    }
}
