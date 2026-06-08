<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum DateTimeFormat: string
{
    case DayMonthYear12Hour = '4';
    case DayMonthYear24Hour = '3';
    case MonthDayYear12Hour = '2';
    case MonthDayYear24Hour = '1';
    case VersionNumber = '0';
}
