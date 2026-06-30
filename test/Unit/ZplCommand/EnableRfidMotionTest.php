<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\EnableRfidMotion;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EnableRfidMotion::class)]
class EnableRfidMotionTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^RMN', (string) new EnableRfidMotion(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^RMY', (string) new EnableRfidMotion(true));
    }
}
