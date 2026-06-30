<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidPowerLevel;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetRfidPowerLevels implements ZplCommand
{
    public const string COMMAND = '^RW';
    public const string FORMAT = '%s,%s';
    public const string FORMAT_WITH_ANTENNA = '%s,%s,%d';

    /** Highest antenna port number the command accepts. */
    public const int MAX_ANTENNA = 2;

    /** Lowest antenna port number the command accepts. */
    public const int MIN_ANTENNA = 1;

    private ?int $antenna;
    private RfidPowerLevel $readPower;
    private RfidPowerLevel $writePower;

    public function __construct(
        RfidPowerLevel $readPower,
        RfidPowerLevel $writePower,
        ?int $antenna,
    ) {
        if ($antenna !== null) {
            ValueAssert::int($antenna, self::MIN_ANTENNA, self::MAX_ANTENNA);
        }

        $this->readPower = $readPower;
        $this->writePower = $writePower;
        $this->antenna = $antenna;
    }

    public function __toString()
    {
        if ($this->antenna === null) {
            return self::COMMAND . sprintf(self::FORMAT, $this->readPower->value, $this->writePower->value);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_ANTENNA,
            $this->readPower->value,
            $this->writePower->value,
            $this->antenna,
        );
    }
}
