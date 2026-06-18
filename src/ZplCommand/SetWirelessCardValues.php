<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WirelessOperatingMode;
use Janisvepris\ZplBuilder\Enum\WirelessPreamble;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetWirelessCardValues implements ZplCommand
{
    public const string COMMAND = '^WS';
    public const string FORMAT = '%s,%s,%s';

    /** Maximum byte length of the ESSID value. */
    public const int MAX_ESSID_BYTES = 32;

    private string $essid;
    private WirelessOperatingMode $operatingMode;
    private WirelessPreamble $preamble;

    /**
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        string $essid,
        WirelessOperatingMode $operatingMode,
        WirelessPreamble $preamble,
    ) {
        ValueAssert::stringLengthBytes($essid, 0, self::MAX_ESSID_BYTES);
        ValueAssert::stringNotContains($essid, ['^', '~', ',']);

        $this->essid = $essid;
        $this->operatingMode = $operatingMode;
        $this->preamble = $preamble;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->essid,
            $this->operatingMode->value,
            $this->preamble->value,
        );
    }
}
