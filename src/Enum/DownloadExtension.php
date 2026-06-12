<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum DownloadExtension: string
{
    /**
     * Bitmap (`B`).
     */
    case Bitmap = 'B';

    /**
     * Raw bitmap, .GRF (`G`).
     */
    case Grf = 'G';

    /**
     * Paintbrush, .PCX (`X`).
     */
    case Pcx = 'X';

    /**
     * Compressed graphic, .PNG (`P`).
     */
    case Png = 'P';

    /**
     * TrueType font, .TTF (`T`).
     */
    case TrueType = 'T';
}
