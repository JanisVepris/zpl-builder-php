<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum PostPrintAction: string
{
    case Applicator = 'A';
    case Cutter = 'C';
    case DelayedCutter = 'D';
    case PeelOff = 'P';
    case Rewind = 'R';
    case TearOff = 'T';
}
