<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetDarkness implements ZplCommand
{
    public const string COMMAND = '~SD';
    public const string FORMAT = '%02d';

    /** Maximum darkness setting the printer accepts. */
    public const int MAX_DARKNESS = 30;

    private int $darkness;

    public function __construct(
        int $darkness,
    ) {
        ValueAssert::int($darkness, 0, self::MAX_DARKNESS);

        $this->darkness = $darkness;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->darkness);
    }
}
