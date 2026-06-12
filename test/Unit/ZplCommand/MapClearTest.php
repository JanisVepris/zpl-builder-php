<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\MapClear;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MapClear::class)]
class MapClearTest extends UnitTestCase
{
    public function testRendersClear(): void
    {
        self::assertSame('^MCY', (string) new MapClear(true));
    }

    public function testRendersRetain(): void
    {
        self::assertSame('^MCN', (string) new MapClear(false));
    }
}
