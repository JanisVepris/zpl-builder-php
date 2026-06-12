<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand\PrintMirror;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PrintMirror::class)]
#[UsesClass(BoolToStr::class)]
class PrintMirrorTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^PMN', (string) new PrintMirror(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^PMY', (string) new PrintMirror(true));
    }
}
