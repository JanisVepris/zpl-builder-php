<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RfidWriteProtect;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class WriteRfidTag implements ZplCommand
{
    public const string COMMAND = '^WT';
    public const string FORMAT = '%d,%d,%s,%s,%s,%s';

    /** Highest number of write retries the command accepts. */
    public const int MAX_RETRIES = 10;

    private int $block;
    private RfidByteFormat $format;
    private RfidMotion $motion;
    private int $retries;
    private bool $verify;
    private RfidWriteProtect $writeProtect;

    /**
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(
        int $block,
        int $retries,
        RfidMotion $motion,
        RfidWriteProtect $writeProtect,
        RfidByteFormat $format,
        bool $verify,
    ) {
        ValueAssert::int($block);
        ValueAssert::int($retries, 0, self::MAX_RETRIES);

        $this->block = $block;
        $this->retries = $retries;
        $this->motion = $motion;
        $this->writeProtect = $writeProtect;
        $this->format = $format;
        $this->verify = $verify;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->block,
            $this->retries,
            $this->motion->value,
            $this->writeProtect->value,
            $this->format->value,
            BoolToStr::conv($this->verify),
        );
    }
}
