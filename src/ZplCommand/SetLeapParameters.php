<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LeapMode;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetLeapParameters implements ZplCommand
{
    public const string COMMAND = '^WL';
    public const string FORMAT = '%s,%s,%s';

    /** Maximum byte length of the LEAP user name and password. */
    public const int MAX_CREDENTIAL_BYTES = 40;

    /** Minimum byte length of the LEAP user name and password. */
    public const int MIN_CREDENTIAL_BYTES = 4;

    private LeapMode $mode;
    private string $password;
    private string $username;

    /**
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function __construct(
        LeapMode $mode,
        string $username,
        string $password,
    ) {
        ValueAssert::stringLengthBytes($username, self::MIN_CREDENTIAL_BYTES, self::MAX_CREDENTIAL_BYTES);
        ValueAssert::stringLengthBytes($password, self::MIN_CREDENTIAL_BYTES, self::MAX_CREDENTIAL_BYTES);
        ValueAssert::stringNotContains($username, ['^', '~', ',']);
        ValueAssert::stringNotContains($password, ['^', '~', ',']);

        $this->mode = $mode;
        $this->username = $username;
        $this->password = $password;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->mode->value,
            $this->username,
            $this->password,
        );
    }
}
