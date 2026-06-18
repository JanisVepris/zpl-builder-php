<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RfidWriteProtect;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class EncodeAfiOrDsfidByte implements ZplCommand
{
    public const string COMMAND = '^WF';
    public const string FORMAT = '%d,%s,%s,%s,%s';

    /** Highest number of write retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private RfidByteType $byteType;
    private RfidByteFormat $format;
    private RfidMotion $motion;
    private int $retries;
    private RfidWriteProtect $writeProtect;

    public function __construct(
        int $retries,
        RfidMotion $motion,
        RfidWriteProtect $writeProtect,
        RfidByteFormat $format,
        RfidByteType $byteType,
    ) {
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->retries = $retries;
        $this->motion = $motion;
        $this->writeProtect = $writeProtect;
        $this->format = $format;
        $this->byteType = $byteType;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->retries,
            $this->motion->value,
            $this->writeProtect->value,
            $this->format->value,
            $this->byteType->value,
        );
    }
}
