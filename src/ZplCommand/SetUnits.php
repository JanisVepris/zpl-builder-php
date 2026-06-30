<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MeasurementUnit;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetUnits implements ZplCommand
{
    public const string COMMAND = '^MU';
    public const string FORMAT = '%s';
    public const string FORMAT_WITH_BASE = '%s,%d';
    public const string FORMAT_WITH_CONVERSION = '%s,%d,%d';

    /** Highest format-base resolution (dpi) the conversion accepts. */
    public const int MAX_BASE_DPI = 300;

    /** Highest target resolution (dpi) the conversion accepts. */
    public const int MAX_CONVERSION_DPI = 600;

    /** Lowest format-base resolution (dpi) the conversion accepts. */
    public const int MIN_BASE_DPI = 150;

    /** Lowest target resolution (dpi) the conversion accepts. */
    public const int MIN_CONVERSION_DPI = 300;

    private ?int $baseDpi;
    private ?int $conversionDpi;
    private MeasurementUnit $unit;

    public function __construct(
        MeasurementUnit $unit,
        ?int $baseDpi,
        ?int $conversionDpi,
    ) {
        if ($baseDpi !== null) {
            ValueAssert::int($baseDpi, self::MIN_BASE_DPI, self::MAX_BASE_DPI);
        }

        if ($conversionDpi !== null) {
            ValueAssert::int($conversionDpi, self::MIN_CONVERSION_DPI, self::MAX_CONVERSION_DPI);
        }

        $this->unit = $unit;
        $this->baseDpi = $baseDpi;
        $this->conversionDpi = $conversionDpi;
    }

    public function __toString()
    {
        if ($this->baseDpi === null) {
            return self::COMMAND . sprintf(self::FORMAT, $this->unit->value);
        }

        if ($this->conversionDpi === null) {
            return self::COMMAND . sprintf(self::FORMAT_WITH_BASE, $this->unit->value, $this->baseDpi);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_CONVERSION,
            $this->unit->value,
            $this->baseDpi,
            $this->conversionDpi,
        );
    }
}
