<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum MediaTrackingType: string
{
    case Continuous = 'N';
    case NonContinuousMark = 'M';
    case NonContinuousWeb = 'Y';
    case NonContinuousWebAlternate = 'W';
}
