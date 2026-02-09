<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Justify: string
{
    case Left = 'L';
    case Center = 'C';
    case Right = 'R';

    /** If J is used the last line is left-justified. */
    case Justified = 'J';
}
