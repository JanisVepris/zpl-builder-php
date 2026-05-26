<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class FieldBlock implements ZplCommand
{
    public const string COMMAND = '^FB';
    public const string FORMAT = '%d,%d,%d,%s,%d';

    /** Per-parameter integer cap for `^FB` width/lines/spacing/indent per the ZPL spec. */
    public const int MAX_PARAM = 9999;
    private int $hangingIndent;
    private Justify $justify;
    private int $lineSpacing;
    private int $maxLines;
    private int $width;

    public function __construct(
        int $width,
        int $maxLines,
        int $lineSpacing,
        Justify $justify,
        int $hangingIndent,
    ) {
        ValueAssert::int($width, 0, self::MAX_PARAM);
        ValueAssert::int($maxLines, 1, self::MAX_PARAM);
        ValueAssert::int($lineSpacing, -self::MAX_PARAM, self::MAX_PARAM);
        ValueAssert::int($hangingIndent, 0, self::MAX_PARAM);

        $this->lineSpacing = $lineSpacing;
        $this->maxLines = $maxLines;
        $this->width = $width;
        $this->justify = $justify;
        $this->hangingIndent = $hangingIndent;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->width,
            $this->maxLines,
            $this->lineSpacing,
            $this->justify->value,
            $this->hangingIndent,
        );
    }
}
