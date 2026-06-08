<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SerializationData implements ZplCommand
{
    public const string COMMAND = '^SN';
    public const string FORMAT = '%s,%s,%s';

    /** Printer command-text buffer limit (bytes) for the `^SN` starting value and increment. */
    public const int MAX_VALUE_BYTES = 3072;

    private string $increment;

    private bool $leadingZeros;

    private string $startValue;

    public function __construct(
        string $startValue,
        string $increment,
        bool $leadingZeros,
    ) {
        ValueAssert::stringLengthBytes($startValue, 1, self::MAX_VALUE_BYTES);
        ValueAssert::stringNotContains($startValue, ['^', '~', ',']);

        ValueAssert::stringLengthBytes($increment, 1, self::MAX_VALUE_BYTES);
        ValueAssert::stringNotContains($increment, ['^', '~', ',']);

        $this->startValue = $startValue;
        $this->increment = $increment;
        $this->leadingZeros = $leadingZeros;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->startValue,
            $this->increment,
            BoolToStr::conv($this->leadingZeros),
        );
    }
}
