<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DataMatrixQuality;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeDataMatrix implements ZplCommand
{
    public const string COMMAND = '^BX';

    /** Orientation, element height, quality. The c/r/f/g parameters are appended only when set. */
    public const string FORMAT = '%s,%d,%s';

    public const int MAX_DIMENSION = 49;
    public const int MAX_FORMAT_ID = 6;
    public const int MIN_DIMENSION = 9;
    public const int MIN_FORMAT_ID = 1;

    private ?int $columns;
    private ?string $escapeChar;
    private ?int $formatId;
    private int $moduleHeight;
    private Orientation $orientation;
    private DataMatrixQuality $quality;
    private ?int $rows;

    public function __construct(
        Orientation $orientation,
        int $moduleHeight,
        DataMatrixQuality $quality,
        ?int $columns,
        ?int $rows,
        ?int $formatId,
        ?string $escapeChar,
    ) {
        ValueAssert::int($moduleHeight);

        if ($columns !== null) {
            ValueAssert::int($columns, self::MIN_DIMENSION, self::MAX_DIMENSION);
        }

        if ($rows !== null) {
            ValueAssert::int($rows, self::MIN_DIMENSION, self::MAX_DIMENSION);
        }

        if ($formatId !== null) {
            ValueAssert::int($formatId, self::MIN_FORMAT_ID, self::MAX_FORMAT_ID);
        }

        if ($escapeChar !== null) {
            ValueAssert::stringLengthBytes($escapeChar, 1, 1);
        }

        $this->orientation = $orientation;
        $this->moduleHeight = $moduleHeight;
        $this->quality = $quality;
        $this->columns = $columns;
        $this->rows = $rows;
        $this->formatId = $formatId;
        $this->escapeChar = $escapeChar;
    }

    public function __toString()
    {
        $base = self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->moduleHeight,
            $this->quality->value,
        );

        $optional = [
            $this->columns === null ? '' : (string) $this->columns,
            $this->rows === null ? '' : (string) $this->rows,
            $this->formatId === null ? '' : (string) $this->formatId,
            $this->escapeChar ?? '',
        ];

        while ($optional !== [] && end($optional) === '') {
            array_pop($optional);
        }

        if ($optional === []) {
            return $base;
        }

        return $base . ',' . implode(',', $optional);
    }
}
