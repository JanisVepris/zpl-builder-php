<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Orientation: string
{
    /**
     * Normal (0 degrees).
     */
    case ROTATE_0 = 'N';

    /**
     * Rotate 90 degrees clockwise (Top to bottom).
     */
    case ROTATE_90 = 'R';

    /**
     * Rotate 180 degrees (Invert).
     */
    case ROTATE_180 = 'I';

    /**
     * Rotate 270 degrees clockwise (Bottom to top).
     */
    case ROTATE_270 = 'B';
}
