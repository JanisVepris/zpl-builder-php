<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Justify: string
{
    case Center = 'C';

    /** If J is used the last line is left-justified. */
    case Justified = 'J';
    case Left = 'L';
    case Right = 'R';
}
