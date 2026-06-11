<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\GraphicFieldCompression;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GraphicField implements ZplCommand
{
    public const string COMMAND = '^GF';
    public const string FORMAT = '%s,%d,%d,%d,%s';

    /** Maximum value accepted by each byte-count parameter (`b`, `c`, `d`). */
    public const int MAX_BYTES = 99999;

    private int $byteCount;
    private int $bytesPerRow;
    private GraphicFieldCompression $compression;
    private string $data;
    private int $fieldCount;

    /**
     * @throws IntegerValueOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        GraphicFieldCompression $compression,
        int $byteCount,
        int $fieldCount,
        int $bytesPerRow,
        string $data,
    ) {
        ValueAssert::int($byteCount, 1, self::MAX_BYTES);
        ValueAssert::int($fieldCount, 1, self::MAX_BYTES);
        ValueAssert::int($bytesPerRow, 1, self::MAX_BYTES);
        // A caret or tilde in the data prematurely aborts the printer's download, so reject them.
        ValueAssert::stringNotContains($data);

        $this->compression = $compression;
        $this->byteCount = $byteCount;
        $this->fieldCount = $fieldCount;
        $this->bytesPerRow = $bytesPerRow;
        $this->data = $data;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->compression->value,
            $this->byteCount,
            $this->fieldCount,
            $this->bytesPerRow,
            $this->data,
        );
    }
}
