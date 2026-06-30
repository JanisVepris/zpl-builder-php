<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintSpeed;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintRate;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintRate::class)]
class PrintRateTest extends UnitTestCase
{
    public function testRendersHighSpeeds(): void
    {
        $command = new PrintRate(PrintSpeed::Ips12, PrintSpeed::Ips10, PrintSpeed::Ips8);

        self::assertSame('^PR12,10,8', (string) $command);
    }

    public function testRendersSpeeds(): void
    {
        $command = new PrintRate(PrintSpeed::Ips2, PrintSpeed::Ips6, PrintSpeed::Ips2);

        self::assertSame('^PR2,6,2', (string) $command);
    }
}
