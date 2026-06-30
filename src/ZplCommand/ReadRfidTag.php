<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidDataOrder;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ReadRfidTag implements ZplCommand
{
    public const string COMMAND = '^RT';
    public const string FORMAT = '%d,%d,%d,%s,%d,%s,%s';

    /** Highest field number the command accepts. */
    public const int MAX_FIELD_NUMBER = 9999;

    /** Highest number of read retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private int $fieldNumber;
    private RfidByteFormat $format;
    private RfidMotion $motion;
    private int $numberOfBlocks;
    private int $retries;
    private RfidDataOrder $specialMode;
    private int $startingBlock;

    public function __construct(
        int $fieldNumber,
        int $startingBlock,
        int $numberOfBlocks,
        RfidByteFormat $format,
        int $retries,
        RfidMotion $motion,
        RfidDataOrder $specialMode,
    ) {
        ValueAssert::int($fieldNumber, 0, self::MAX_FIELD_NUMBER);
        ValueAssert::int($startingBlock);
        ValueAssert::int($numberOfBlocks, 1);
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->fieldNumber = $fieldNumber;
        $this->startingBlock = $startingBlock;
        $this->numberOfBlocks = $numberOfBlocks;
        $this->format = $format;
        $this->retries = $retries;
        $this->motion = $motion;
        $this->specialMode = $specialMode;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->fieldNumber,
            $this->startingBlock,
            $this->numberOfBlocks,
            $this->format->value,
            $this->retries,
            $this->motion->value,
            $this->specialMode->value,
        );
    }
}
