<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetWirelessPassword implements ZplCommand
{
    public const string COMMAND = '^WP';
    public const string FORMAT = '%04d,%04d';

    /** Highest four-digit wireless password the printer accepts. */
    public const int MAX_PASSWORD = 9999;

    private int $newPassword;
    private int $oldPassword;

    /**
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(
        int $oldPassword,
        int $newPassword,
    ) {
        ValueAssert::int($oldPassword, 0, self::MAX_PASSWORD);
        ValueAssert::int($newPassword, 0, self::MAX_PASSWORD);

        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->oldPassword,
            $this->newPassword,
        );
    }
}
