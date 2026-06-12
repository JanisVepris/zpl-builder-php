<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum GraphicFieldCompression: string
{
    /**
     * ASCII hexadecimal data, following the convention used by the other download
     * commands (`A`).
     */
    case AsciiHex = 'A';

    /**
     * Strictly binary data sent from the host (`B`).
     */
    case Binary = 'B';

    /**
     * Binary data compressed with Zebra's compression algorithm (`C`).
     */
    case CompressedBinary = 'C';
}
