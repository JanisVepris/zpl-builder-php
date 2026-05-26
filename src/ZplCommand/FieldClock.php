<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldClock implements ZplCommand
{
    public const string COMMAND = '^FC';
    public const string FORMAT = '%s';
    public const string FORMAT_WITH_SECONDARY = '%s,%s';
    public const string FORMAT_WITH_TERTIARY = '%s,%s,%s';

    private string $primary;
    private ?string $secondary;
    private ?string $tertiary;

    public function __construct(
        string $primary,
        ?string $secondary = null,
        ?string $tertiary = null,
    ) {
        self::assertIndicator($primary);

        if ($secondary !== null) {
            self::assertIndicator($secondary);

            if ($secondary === $primary) {
                throw new DuplicateClockIndicatorException($secondary, 'secondary', 'primary');
            }
        }

        if ($tertiary !== null) {
            if ($secondary === null) {
                throw new TertiaryClockIndicatorWithoutSecondaryException($tertiary);
            }

            self::assertIndicator($tertiary);

            if ($tertiary === $primary) {
                throw new DuplicateClockIndicatorException($tertiary, 'tertiary', 'primary');
            }

            if ($tertiary === $secondary) {
                throw new DuplicateClockIndicatorException($tertiary, 'tertiary', 'secondary');
            }
        }

        $this->primary = $primary;
        $this->secondary = $secondary;
        $this->tertiary = $tertiary;
    }

    public function __toString()
    {
        if ($this->secondary === null) {
            return self::COMMAND . sprintf(self::FORMAT, $this->primary);
        }

        if ($this->tertiary === null) {
            return self::COMMAND . sprintf(self::FORMAT_WITH_SECONDARY, $this->primary, $this->secondary);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_TERTIARY,
            $this->primary,
            $this->secondary,
            $this->tertiary,
        );
    }

    private static function assertIndicator(string $indicator): void
    {
        ValueAssert::stringLengthBytes($indicator, 1, 1);
        ValueAssert::stringNotContains($indicator, ['^', '~', ',']);
    }
}
