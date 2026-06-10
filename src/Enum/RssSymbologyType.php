<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * Symbology type within the RSS-14 family for the `^BR` command.
 */
enum RssSymbologyType: string
{
    case Ean13 = '9';
    case Ean8 = '10';
    case Rss14 = '1';
    case Rss14Stacked = '3';
    case Rss14StackedOmnidirectional = '4';
    case Rss14Truncated = '2';
    case RssExpanded = '6';
    case RssLimited = '5';
    case UccEan128CcAB = '11';
    case UccEan128CcC = '12';
    case UpcA = '7';
    case UpcE = '8';
}
