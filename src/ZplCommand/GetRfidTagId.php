<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidDataOrder;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class GetRfidTagId implements ZplCommand
{
    public const string COMMAND = '^RI';
    public const string FORMAT = '%d,%s,%d,%s';

    /** Highest field number the command accepts. */
    public const int MAX_FIELD_NUMBER = 9999;

    /** Highest number of read retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private RfidDataOrder $dataOrder;
    private int $fieldNumber;
    private RfidMotion $motion;
    private int $retries;

    public function __construct(
        int $fieldNumber,
        RfidDataOrder $dataOrder,
        int $retries,
        RfidMotion $motion,
    ) {
        ValueAssert::int($fieldNumber, 0, self::MAX_FIELD_NUMBER);
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->fieldNumber = $fieldNumber;
        $this->dataOrder = $dataOrder;
        $this->retries = $retries;
        $this->motion = $motion;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->fieldNumber,
            $this->dataOrder->value,
            $this->retries,
            $this->motion->value,
        );
    }
}
