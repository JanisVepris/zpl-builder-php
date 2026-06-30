<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class PrinterSleep implements ZplCommand
{
    public const string COMMAND = '^ZZ';
    public const string FORMAT = '%d,%s';

    /** Maximum idle time before shutdown, in seconds. */
    public const int MAX_IDLE_SECONDS = 999999;

    private int $idleSeconds;
    private bool $shutdownWithLabelsQueued;

    public function __construct(
        int $idleSeconds,
        bool $shutdownWithLabelsQueued,
    ) {
        ValueAssert::int($idleSeconds, 0, self::MAX_IDLE_SECONDS);

        $this->idleSeconds = $idleSeconds;
        $this->shutdownWithLabelsQueued = $shutdownWithLabelsQueued;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->idleSeconds,
            BoolToStr::conv($this->shutdownWithLabelsQueued),
        );
    }
}
