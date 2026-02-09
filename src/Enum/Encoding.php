<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Enum;

enum Encoding: string
{
    case USA1 = '0';
    case USA2 = '1';
    case UK = '2';
    case HOLLAND = '3';
    case DENMARK_NORWAY = '4';
    case SWEDEN_FINLAND = '5';
    case GERMANY = '6';
    case FRANCE1 = '7';
    case FRANCE2 = '8';
    case ITALY = '9';
    case SPAIN = '10';
    case MISC = '11';
    case JAPAN_ASCII = '12';
    case CODE_PAGE_850 = '13';
    case DOUBLE_BYTE_ASIAN = '14';
    case SHIFT_JIS2 = '15';
    case EUC_JP_EUC_CN1 = '16';
    case UCS_2_BIG_ENDIAN3 = '17';
    case SINGLE_BYTE_ASIAN = '24';
    case MULTIBYTE_ASIAN_ASCII_TRANSPARENCY1_4 = '26';
    case CODE_PAGE_1252 = '27';
    case UTF8 = '28';
    case UTF16_BIG_ENDIAN = '29';
    case UTF16_LITTLE_ENDIAN = '30';
    case ZEBRA_CODE_PAGE_1250 = '31';
    case CODE_PAGE_1251 = '33';
    case CODE_PAGE_1253 = '34';
    case CODE_PAGE_1254 = '35';
    case CODE_PAGE_1255 = '36';
}
