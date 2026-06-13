<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidLockStyle;
use Janisvepris\ZplBuilder\Enum\RfidPasswordMemoryBank;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetRfidTagPassword implements ZplCommand
{
    public const string COMMAND = '^RZ';
    public const string FORMAT = '%s';
    public const string FORMAT_WITH_BANK = '%s,%s';
    public const string FORMAT_WITH_LOCK = '%s,%s,%s';

    /** Largest password byte length the command accepts (32-bit Gen 2 password). */
    public const int MAX_PASSWORD_BYTES = 8;

    private ?RfidLockStyle $lockStyle;
    private ?RfidPasswordMemoryBank $memoryBank;
    private string $password;

    public function __construct(
        string $password,
        ?RfidPasswordMemoryBank $memoryBank,
        ?RfidLockStyle $lockStyle,
    ) {
        ValueAssert::stringLengthBytes($password, 1, self::MAX_PASSWORD_BYTES);
        ValueAssert::hexValue($password);

        $this->password = $password;
        $this->memoryBank = $memoryBank;
        $this->lockStyle = $lockStyle;
    }

    public function __toString()
    {
        if ($this->memoryBank === null) {
            return self::COMMAND . sprintf(self::FORMAT, $this->password);
        }

        if ($this->lockStyle === null) {
            return self::COMMAND . sprintf(self::FORMAT_WITH_BANK, $this->password, $this->memoryBank->value);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_LOCK,
            $this->password,
            $this->memoryBank->value,
            $this->lockStyle->value,
        );
    }
}
