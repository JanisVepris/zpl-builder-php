<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Util;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BoolToStr::class)]
class BoolToStrTest extends UnitTestCase
{
    public function testTrueConvertsToY(): void
    {
        self::assertSame('Y', BoolToStr::conv(true));
    }

    public function testFalseConvertsToN(): void
    {
        self::assertSame('N', BoolToStr::conv(false));
    }
}
