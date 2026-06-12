<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum MediaFeedAction: string
{
    case Calibrate = 'C';
    case Feed = 'F';
    case LengthDetect = 'L';
    case None = 'N';
}
