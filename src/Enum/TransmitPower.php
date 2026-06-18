<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum TransmitPower: string
{
    case Power1 = '1';
    case Power100 = '100';
    case Power20 = '20';
    case Power30 = '30';
    case Power5 = '5';
    case Power50 = '50';
}
