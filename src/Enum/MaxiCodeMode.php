<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum MaxiCodeMode: string
{
    /**
     * Full EEC.
     */
    case FullEec = '5';

    /**
     * Reader program, secretary.
     */
    case ReaderProgram = '6';

    /**
     * Standard symbol, secretary.
     */
    case StandardSymbol = '4';

    /**
     * Structured Carrier Message with an alphanumeric postal code (non-U.S.).
     */
    case StructuredCarrierAlphanumeric = '3';

    /**
     * Structured Carrier Message with a numeric postal code (U.S.).
     */
    case StructuredCarrierNumeric = '2';
}
