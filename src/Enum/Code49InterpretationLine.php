<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Code49InterpretationLine: string
{
    /**
     * Print the interpretation line above the bar code.
     */
    case Above = 'A';

    /**
     * Print the interpretation line below the bar code.
     */
    case Below = 'B';

    /**
     * No interpretation line printed.
     */
    case None = 'N';
}
