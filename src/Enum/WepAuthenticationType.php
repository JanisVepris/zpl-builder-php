<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum WepAuthenticationType: string
{
    case OpenSystem = 'O';
    case SharedKey = 'S';
}
