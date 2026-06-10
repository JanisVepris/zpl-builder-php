<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum CodablockMode: string
{
    /**
     * CODABLOCK A — uses the Code 39 character set.
     */
    case ModeA = 'A';

    /**
     * CODABLOCK E — uses the Code 128 character set and automatically adds FNC1.
     */
    case ModeE = 'E';

    /**
     * CODABLOCK F — uses the Code 128 character set.
     */
    case ModeF = 'F';
}
