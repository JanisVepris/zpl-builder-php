<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ReadAfiOrDsfidByte implements ZplCommand
{
    public const string COMMAND = '^RA';
    public const string FORMAT = '%d,%s,%d,%s,%s';

    /** Highest field number the command accepts. */
    public const int MAX_FIELD_NUMBER = 9999;

    /** Highest number of read retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private RfidByteType $byteType;
    private int $fieldNumber;
    private RfidByteFormat $format;
    private RfidMotion $motion;
    private int $retries;

    public function __construct(
        int $fieldNumber,
        RfidByteFormat $format,
        int $retries,
        RfidMotion $motion,
        RfidByteType $byteType,
    ) {
        ValueAssert::int($fieldNumber, 0, self::MAX_FIELD_NUMBER);
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->fieldNumber = $fieldNumber;
        $this->format = $format;
        $this->retries = $retries;
        $this->motion = $motion;
        $this->byteType = $byteType;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->fieldNumber,
            $this->format->value,
            $this->retries,
            $this->motion->value,
            $this->byteType->value,
        );
    }
}
