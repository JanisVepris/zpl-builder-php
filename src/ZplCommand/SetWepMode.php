<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WepAuthenticationType;
use Janisvepris\ZplBuilder\Enum\WepEncryptionMode;
use Janisvepris\ZplBuilder\Enum\WepKeyStorage;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetWepMode implements ZplCommand
{
    public const string COMMAND = '^WE';
    public const string FORMAT = '%s';

    /** Highest encryption-key index the command accepts. */
    public const int MAX_INDEX = 4;

    /** Lowest encryption-key index the command accepts. */
    public const int MIN_INDEX = 1;

    private ?WepAuthenticationType $authentication;
    private ?int $index;
    private ?string $key1;
    private ?string $key2;
    private ?string $key3;
    private ?string $key4;
    private ?WepKeyStorage $keyStorage;
    private WepEncryptionMode $mode;

    public function __construct(
        WepEncryptionMode $mode,
        ?int $index,
        ?WepAuthenticationType $authentication,
        ?WepKeyStorage $keyStorage,
        ?string $key1,
        ?string $key2,
        ?string $key3,
        ?string $key4,
    ) {
        if ($index !== null) {
            ValueAssert::int($index, self::MIN_INDEX, self::MAX_INDEX);
        }

        foreach ([$key1, $key2, $key3, $key4] as $key) {
            if ($key !== null) {
                ValueAssert::stringNotContains($key, ['^', '~', ',']);
            }
        }

        $this->mode = $mode;
        $this->index = $index;
        $this->authentication = $authentication;
        $this->keyStorage = $keyStorage;
        $this->key1 = $key1;
        $this->key2 = $key2;
        $this->key3 = $key3;
        $this->key4 = $key4;
    }

    public function __toString()
    {
        $output = self::COMMAND . sprintf(self::FORMAT, $this->mode->value);

        $optional = [
            $this->index === null ? null : (string) $this->index,
            $this->authentication?->value,
            $this->keyStorage?->value,
            $this->key1,
            $this->key2,
            $this->key3,
            $this->key4,
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
