<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Orientation: string
{
    /**
     * Normal (0 degrees).
     */
    case Rotate0 = 'N';

    /**
     * Rotate 90 degrees clockwise (Top to bottom).
     */
    case Rotate90 = 'R';

    /**
     * Rotate 180 degrees (Invert).
     */
    case Rotate180 = 'I';

    /**
     * Rotate 270 degrees clockwise (Bottom to top).
     */
    case Rotate270 = 'B';
}
