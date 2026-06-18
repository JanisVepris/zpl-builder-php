<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\IpResolution;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ChangeWirelessNetworkSettings implements ZplCommand
{
    public const string COMMAND = '^WI';
    public const string FORMAT = '%s,%s,%s,%s';

    /** Highest base RAW port number the printer accepts. */
    public const int MAX_PORT_NUMBER = 99999;

    /** Highest connection timeout, in seconds, the printer accepts. */
    public const int MAX_TIMEOUT_SECONDS = 9999;

    private ?int $arpInterval;
    private ?int $basePortNumber;
    private ?bool $connectionTimeoutChecking;
    private string $defaultGateway;
    private string $ipAddress;
    private IpResolution $ipResolution;
    private string $subnetMask;
    private ?int $timeoutValue;
    private ?string $winsServer;

    public function __construct(
        IpResolution $ipResolution,
        string $ipAddress,
        string $subnetMask,
        string $defaultGateway,
        ?string $winsServer,
        ?bool $connectionTimeoutChecking,
        ?int $timeoutValue,
        ?int $arpInterval,
        ?int $basePortNumber,
    ) {
        ValueAssert::stringNotContains($ipAddress, ['^', '~', ',']);
        ValueAssert::stringNotContains($subnetMask, ['^', '~', ',']);
        ValueAssert::stringNotContains($defaultGateway, ['^', '~', ',']);

        if ($winsServer !== null) {
            ValueAssert::stringNotContains($winsServer, ['^', '~', ',']);
        }

        if ($timeoutValue !== null) {
            ValueAssert::int($timeoutValue, 0, self::MAX_TIMEOUT_SECONDS);
        }

        if ($arpInterval !== null) {
            ValueAssert::int($arpInterval);
        }

        if ($basePortNumber !== null) {
            ValueAssert::int($basePortNumber, 0, self::MAX_PORT_NUMBER);
        }

        $this->ipResolution = $ipResolution;
        $this->ipAddress = $ipAddress;
        $this->subnetMask = $subnetMask;
        $this->defaultGateway = $defaultGateway;
        $this->winsServer = $winsServer;
        $this->connectionTimeoutChecking = $connectionTimeoutChecking;
        $this->timeoutValue = $timeoutValue;
        $this->arpInterval = $arpInterval;
        $this->basePortNumber = $basePortNumber;
    }

    public function __toString()
    {
        $output = self::COMMAND . sprintf(
            self::FORMAT,
            $this->ipResolution->value,
            $this->ipAddress,
            $this->subnetMask,
            $this->defaultGateway,
        );

        $optional = [
            $this->winsServer,
            $this->connectionTimeoutChecking === null ? null : BoolToStr::conv($this->connectionTimeoutChecking),
            $this->timeoutValue === null ? null : (string) $this->timeoutValue,
            $this->arpInterval === null ? null : (string) $this->arpInterval,
            $this->basePortNumber === null ? null : (string) $this->basePortNumber,
        ];

        $lastSet = -1;
        foreach ($optional as $index => $value) {
            if ($value !== null) {
                $lastSet = $index;
            }
        }

        for ($i = 0; $i <= $lastSet; ++$i) {
            $output .= ',' . ($optional[$i] ?? '');
        }

        return $output;
    }
}
