<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * The four characters Codabar (`^BK`) accepts as a start or stop character.
 */
enum CodabarCharacter: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
}
