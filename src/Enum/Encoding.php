<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Encoding: string
{
    case Usa1 = '0';
    case Usa2 = '1';
    case Uk = '2';
    case Holland = '3';
    case DenmarkNorway = '4';
    case SwedenFinland = '5';
    case Germany = '6';
    case France1 = '7';
    case France2 = '8';
    case Italy = '9';
    case Spain = '10';
    case Misc = '11';
    case JapanAscii = '12';
    case CodePage850 = '13';
    case DoubleByteAsian = '14';
    case ShiftJis2 = '15';
    case EucJpEucCn1 = '16';
    case Ucs2BigEndian3 = '17';
    case SingleByteAsian = '24';
    case MultibyteAsianAsciiTransparency1To4 = '26';
    case CodePage1252 = '27';
    case Utf8 = '28';
    case Utf16BigEndian = '29';
    case Utf16LittleEndian = '30';
    case ZebraCodePage1250 = '31';
    case CodePage1251 = '33';
    case CodePage1253 = '34';
    case CodePage1254 = '35';
    case CodePage1255 = '36';
}
