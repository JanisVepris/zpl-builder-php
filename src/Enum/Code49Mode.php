<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Code49Mode: string
{
    /**
     * Automatic Mode — the printer determines the starting mode by analyzing the field data.
     */
    case Automatic = 'A';

    /**
     * Group Alphanumeric Mode.
     */
    case GroupAlphanumeric = '3';

    /**
     * Multiple Read Alphanumeric.
     */
    case MultipleReadAlphanumeric = '1';

    /**
     * Regular Alphanumeric Mode.
     */
    case RegularAlphanumeric = '0';

    /**
     * Regular Alphanumeric Shift 1.
     */
    case RegularAlphanumericShift1 = '4';

    /**
     * Regular Alphanumeric Shift 2.
     */
    case RegularAlphanumericShift2 = '5';

    /**
     * Regular Numeric Mode.
     */
    case RegularNumeric = '2';
}
