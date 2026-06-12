<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DownloadExtension;
use Janisvepris\ZplBuilder\Enum\DownloadFormat;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class DownloadObject implements ZplCommand
{
    public const string COMMAND = '~DY';
    public const string FORMAT = '%s:%s,%s,%s,%d,%d,%s';

    /**
     * Upper bound applied to the byte-count parameters (`t`, `w`). The spec sets no maximum — the
     * true limit is the printer's available memory — so this is a generous library-imposed sanity
     * cap rather than a hardware limit.
     */
    public const int MAX_BYTES = 99999999;

    /** Maximum byte length of an object name. */
    public const int MAX_NAME_BYTES = 16;

    private int $bytesPerRow;
    private string $data;
    private StorageDevice $device;
    private DownloadExtension $extension;
    private DownloadFormat $format;
    private string $name;
    private int $totalBytes;

    /**
     * `$data` may be empty when the file is sent to the printer as a separate transmission, per the
     * `~DY` spec. A caret or tilde in `$data` is rejected — either would abort the download.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        StorageDevice $device,
        string $name,
        DownloadFormat $format,
        DownloadExtension $extension,
        int $totalBytes,
        int $bytesPerRow,
        string $data,
    ) {
        ValueAssert::stringLengthBytes($name, 1, self::MAX_NAME_BYTES);
        ValueAssert::int($totalBytes, 1, self::MAX_BYTES);
        ValueAssert::int($bytesPerRow, 0, self::MAX_BYTES);
        ValueAssert::stringNotContains($data);

        $this->device = $device;
        $this->name = $name;
        $this->format = $format;
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
            $this->format->value,
            $this->extension->value,
            $this->totalBytes,
            $this->bytesPerRow,
            $this->data,
        );
    }
}
