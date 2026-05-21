<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

final readonly class FieldBlock implements ZplCommand
{
    private const string FORMAT = '^FB%d,%d,%d,%s,%d';
    private int $width;
    private int $maxLines;
    private int $lineSpacing;
    private Justify $justify;
    private int $hangingIndent;

    public function __construct(
        int $width,
        int $maxLines,
        int $lineSpacing,
        Justify $justify,
        int $hangingIndent,
    ) {
        ValueAssert::int($width, 0, 9999);
        ValueAssert::int($maxLines, 1, 9999);
        ValueAssert::int($lineSpacing, -9999, 9999);
        ValueAssert::int($hangingIndent, 0, 9999);

        $this->lineSpacing = $lineSpacing;
        $this->maxLines = $maxLines;
        $this->width = $width;
        $this->justify = $justify;
        $this->hangingIndent = $hangingIndent;
    }

    public function __toString()
    {
        return sprintf(
            self::FORMAT,
            $this->width,
            $this->maxLines,
            $this->lineSpacing,
            $this->justify->value,
            $this->hangingIndent,
        );
    }
}
