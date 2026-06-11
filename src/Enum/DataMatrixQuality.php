<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * Data Matrix (`^BX`) quality level — the amount of error-correction data added to the
 * symbol, referred to as the ECC value in the AIM specification. ECC 200 (Reed-Solomon)
 * is recommended for new applications.
 */
enum DataMatrixQuality: string
{
    case Ecc0 = '0';
    case Ecc100 = '100';
    case Ecc140 = '140';
    case Ecc200 = '200';
    case Ecc50 = '50';
    case Ecc80 = '80';
}
