<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * Check-digit scheme for the MSI bar code (`^BM`).
 */
enum MsiCheckDigit: string
{
    /**
     * No check digits.
     */
    case None = 'A';

    /**
     * One Mod 10 check digit.
     */
    case OneMod10 = 'B';

    /**
     * One Mod 11 and one Mod 10 check digit.
     */
    case OneMod11AndOneMod10 = 'D';

    /**
     * Two Mod 10 check digits.
     */
    case TwoMod10 = 'C';
}
