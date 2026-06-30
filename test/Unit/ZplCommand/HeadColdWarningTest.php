<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\HeadColdWarning;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HeadColdWarning::class)]
class HeadColdWarningTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^MWN', (string) new HeadColdWarning(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^MWY', (string) new HeadColdWarning(true));
    }
}
