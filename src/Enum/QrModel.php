<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

/**
 * QR Code model for the `^BQ` command.
 */
enum QrModel: string
{
    /**
     * Model 1 — the original QR Code specification.
     */
    case Model1 = '1';

    /**
     * Model 2 — the enhanced (and recommended) QR Code specification.
     */
    case Model2 = '2';
}
