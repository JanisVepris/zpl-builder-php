<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum FontExtension: string
{
    /**
     * Bitmapped or scalable font (`.FNT`).
     */
    case Font = 'FNT';

    /**
     * TrueType font (`.TTF`).
     */
    case TrueType = 'TTF';

    /**
     * TrueType extension/collection font (`.TTE`).
     */
    case TrueTypeExtension = 'TTE';
}
