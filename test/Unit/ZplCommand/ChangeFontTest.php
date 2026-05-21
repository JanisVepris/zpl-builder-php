<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ChangeFont;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ChangeFont::class)]
class ChangeFontTest extends UnitTestCase
{
    public function testRendersWithLetterFont(): void
    {
        self::assertSame('^CFA,30,15', (string) new ChangeFont(Font::A, 30, 15));
    }

    public function testRendersWithNumericFont(): void
    {
        self::assertSame('^CF0,42,5', (string) new ChangeFont(Font::Zero, 42, 5));
    }
}
