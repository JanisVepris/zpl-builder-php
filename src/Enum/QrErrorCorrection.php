<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * QR Code error-correction level for the `^BQ` command.
 */
enum QrErrorCorrection: string
{
    /**
     * High density level (~7% recovery).
     */
    case HighDensity = 'L';

    /**
     * High reliability level (~25% recovery).
     */
    case HighReliability = 'Q';

    /**
     * Standard level (~15% recovery).
     */
    case Standard = 'M';

    /**
     * Ultra-high reliability level (~30% recovery).
     */
    case UltraHighReliability = 'H';
}
