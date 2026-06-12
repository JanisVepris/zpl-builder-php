<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum DiagonalOrientation: string
{
    /**
     * Left-leaning diagonal (`\`), running top-left to bottom-right.
     */
    case LeftLeaning = 'L';

    /**
     * Right-leaning diagonal (`/`), running bottom-left to top-right.
     */
    case RightLeaning = 'R';
}
