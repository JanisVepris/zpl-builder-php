<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum DownloadFormat: string
{
    /**
     * Zebra BAR-ONE v5 AR-compressed data (`C`).
     */
    case ArCompressed = 'C';

    /**
     * Portable Network Graphic (.PNG), ZB64-encoded (`P`).
     */
    case Png = 'P';

    /**
     * Uncompressed, ZB64-encoded ASCII data (`A`).
     */
    case UncompressedAscii = 'A';

    /**
     * Uncompressed binary data (`B`).
     */
    case UncompressedBinary = 'B';
}
