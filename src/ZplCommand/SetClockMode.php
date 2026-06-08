<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ClockLanguage;
use Janisvepris\ZplBuilder\Enum\ClockMode;
use Janisvepris\ZplBuilder\Exception\ConflictingClockModeException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetClockMode implements ZplCommand
{
    public const string COMMAND = '^SL';
    public const string FORMAT = '%s';
    public const string FORMAT_WITH_LANGUAGE = '%s,%s';

    /** Maximum time-accuracy tolerance in seconds accepted by ^SL per the ZPL II Programming Guide. */
    public const int MAX_TOLERANCE_SECONDS = 999;

    private ?ClockLanguage $language;
    private ClockMode $mode;
    private ?int $toleranceSeconds;

    /**
     * Slot `a` is either a ClockMode or a numeric tolerance, never both.
     * Slot `b` (language) is optional-trailing — a null language emits no second slot.
     *
     * @throws ConflictingClockModeException
     * @throws IntegerValueOutOfRangeException
     */
    public function __construct(
        ?ClockMode $mode,
        ?int $toleranceSeconds,
        ?ClockLanguage $language,
    ) {
        if ($mode !== null && $toleranceSeconds !== null) {
            throw new ConflictingClockModeException();
        }

        if ($toleranceSeconds !== null) {
            ValueAssert::int($toleranceSeconds, 0, self::MAX_TOLERANCE_SECONDS);
        }

        $this->mode = $mode ?? ClockMode::StartTime;
        $this->toleranceSeconds = $toleranceSeconds;
        $this->language = $language;
    }

    public function __toString()
    {
        $modeToken = $this->toleranceSeconds !== null
            ? (string) $this->toleranceSeconds
            : $this->mode->value;

        if ($this->language === null) {
            return self::COMMAND . sprintf(self::FORMAT, $modeToken);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_LANGUAGE,
            $modeToken,
            $this->language->value,
        );
    }
}
