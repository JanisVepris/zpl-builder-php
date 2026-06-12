<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SuppressBackfeed;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SuppressBackfeed::class)]
class SuppressBackfeedTest extends UnitTestCase
{
    public function testRendersSuppressBackfeedCommand(): void
    {
        self::assertSame('^XB', (string) new SuppressBackfeed());
    }
}
