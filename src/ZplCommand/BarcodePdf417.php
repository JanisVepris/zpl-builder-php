<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodePdf417 implements ZplCommand
{
    public const string COMMAND = '^B7';
    public const string FORMAT = '%s,%d,%d,%s,%s,%s';
    public const int MAX_COLUMNS = 30;
    public const int MAX_ROWS = 90;

    public const int MAX_SECURITY_LEVEL = 8;
    public const int MIN_ROWS = 3;

    private ?int $columns;
    private int $height;
    private Orientation $orientation;
    private ?int $rows;
    private int $securityLevel;
    private bool $truncate;

    public function __construct(
        Orientation $orientation,
        int $height,
        int $securityLevel,
        ?int $columns,
        ?int $rows,
        bool $truncate,
    ) {
        ValueAssert::int($height, 1);
        ValueAssert::int($securityLevel, 0, self::MAX_SECURITY_LEVEL);

        if ($columns !== null) {
            ValueAssert::int($columns, 1, self::MAX_COLUMNS);
        }

        if ($rows !== null) {
            ValueAssert::int($rows, self::MIN_ROWS, self::MAX_ROWS);
        }

        $this->orientation = $orientation;
        $this->height = $height;
        $this->securityLevel = $securityLevel;
        $this->columns = $columns;
        $this->rows = $rows;
        $this->truncate = $truncate;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->orientation->value,
            $this->height,
            $this->securityLevel,
            $this->columns === null ? '' : (string) $this->columns,
            $this->rows === null ? '' : (string) $this->rows,
            BoolToStr::conv($this->truncate),
        );
    }
}
