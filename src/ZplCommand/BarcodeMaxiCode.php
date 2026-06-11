<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MaxiCodeMode;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeMaxiCode implements ZplCommand
{
    public const string COMMAND = '^BD';
    public const string FORMAT = '%s,%d,%d';

    public const int MAX_SYMBOLS = 8;
    public const int MIN_SYMBOLS = 1;

    private MaxiCodeMode $mode;
    private int $symbolNumber;
    private int $totalSymbols;

    public function __construct(
        MaxiCodeMode $mode,
        int $symbolNumber,
        int $totalSymbols,
    ) {
        ValueAssert::int($symbolNumber, self::MIN_SYMBOLS, self::MAX_SYMBOLS);
        ValueAssert::int($totalSymbols, self::MIN_SYMBOLS, self::MAX_SYMBOLS);

        $this->mode = $mode;
        $this->symbolNumber = $symbolNumber;
        $this->totalSymbols = $totalSymbols;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->mode->value,
            $this->symbolNumber,
            $this->totalSymbols,
        );
    }
}
