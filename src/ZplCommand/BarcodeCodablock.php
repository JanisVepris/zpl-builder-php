<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CodablockMode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeCodablock implements ZplCommand
{
    public const string COMMAND = '^BB';
    public const string FORMAT = '%s,%d,%s,%s,%s,%s';

    public const int MAX_CHARACTERS_PER_ROW = 62;
    public const int MAX_ROWS_MODE_A = 22;
    public const int MAX_ROWS_MODE_EF = 4;
    public const int MIN_CHARACTERS_PER_ROW = 2;
    public const int MIN_ROW_HEIGHT = 2;
    public const int MIN_ROWS_MODE_A = 1;
    public const int MIN_ROWS_MODE_EF = 2;

    private ?int $charactersPerRow;
    private CodablockMode $mode;
    private Orientation $orientation;
    private int $rowHeight;
    private ?int $rows;
    private bool $security;

    public function __construct(
        Orientation $orientation,
        int $rowHeight,
        bool $security,
        ?int $charactersPerRow,
        ?int $rows,
        CodablockMode $mode,
    ) {
        ValueAssert::int($rowHeight, self::MIN_ROW_HEIGHT);

        if ($charactersPerRow !== null) {
            ValueAssert::int($charactersPerRow, self::MIN_CHARACTERS_PER_ROW, self::MAX_CHARACTERS_PER_ROW);
        }

        if ($rows !== null) {
            if ($mode === CodablockMode::ModeA) {
                ValueAssert::int($rows, self::MIN_ROWS_MODE_A, self::MAX_ROWS_MODE_A);
            } else {
                ValueAssert::int($rows, self::MIN_ROWS_MODE_EF, self::MAX_ROWS_MODE_EF);
            }
        }

        $this->orientation = $orientation;
        $this->rowHeight = $rowHeight;
        $this->security = $security;
        $this->charactersPerRow = $charactersPerRow;
        $this->rows = $rows;
        $this->mode = $mode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->rowHeight,
            BoolToStr::conv($this->security),
            $this->charactersPerRow === null ? '' : (string) $this->charactersPerRow,
            $this->rows === null ? '' : (string) $this->rows,
            $this->mode->value,
        );
    }
}
