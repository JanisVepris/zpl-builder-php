<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class NetworkConnect implements ZplCommand
{
    public const string COMMAND = '~NC';
    public const string FORMAT = '%03d';

    /** Highest network ID the printer accepts. */
    public const int MAX_ID = 999;

    /** Lowest network ID the printer accepts. */
    public const int MIN_ID = 1;

    private int $networkId;

    /**
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(
        int $networkId,
    ) {
        ValueAssert::int($networkId, self::MIN_ID, self::MAX_ID);

        $this->networkId = $networkId;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->networkId);
    }
}
