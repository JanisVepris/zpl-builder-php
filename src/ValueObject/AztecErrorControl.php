<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ValueObject;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Util\ValueAssert;

/**
 * The Aztec (`^B0`) "error control and symbol size/type" parameter (`d`).
 *
 * The spec packs five disjoint meanings into one numeric field — a default level, a
 * minimum error-correction percentage, a compact-symbol layer count, a full-range-symbol
 * layer count, and the Aztec "Rune". Each named constructor produces one of those, so only
 * spec-valid wire values (`0`, `1..99`, `101..104`, `201..232`, `300`) are representable.
 */
readonly class AztecErrorControl
{
    public const int MAX_COMPACT_LAYERS = 4;
    public const int MAX_ERROR_CORRECTION_PERCENTAGE = 99;
    public const int MAX_FULL_RANGE_LAYERS = 32;

    private const int COMPACT_BASE = 100;
    private const int FULL_RANGE_BASE = 200;
    private const int RUNE_VALUE = 300;

    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * A compact symbol of 1–4 layers (wire values `101..104`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public static function compactSymbol(int $layers): self
    {
        ValueAssert::int($layers, 1, self::MAX_COMPACT_LAYERS);

        return new self(self::COMPACT_BASE + $layers);
    }

    /** The default error correction level (`0`). */
    public static function defaultLevel(): self
    {
        return new self(0);
    }

    /**
     * A minimum error-correction percentage (`1..99`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public static function errorCorrectionPercentage(int $percentage): self
    {
        ValueAssert::int($percentage, 1, self::MAX_ERROR_CORRECTION_PERCENTAGE);

        return new self($percentage);
    }

    /**
     * A full-range symbol of 1–32 layers (wire values `201..232`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public static function fullRangeSymbol(int $layers): self
    {
        ValueAssert::int($layers, 1, self::MAX_FULL_RANGE_LAYERS);

        return new self(self::FULL_RANGE_BASE + $layers);
    }

    /** A simple Aztec "Rune" (`300`). */
    public static function rune(): self
    {
        return new self(self::RUNE_VALUE);
    }

    public function value(): int
    {
        return $this->value;
    }
}
