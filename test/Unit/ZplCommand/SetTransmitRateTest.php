<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\TransmitPower;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand\SetTransmitRate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetTransmitRate::class)]
#[UsesClass(BoolToStr::class)]
class SetTransmitRateTest extends UnitTestCase
{
    public function testRendersAllRatesEnabled(): void
    {
        $command = new SetTransmitRate(true, true, true, true, TransmitPower::Power100);

        self::assertSame('^WRY,Y,Y,Y,100', (string) $command);
    }

    public function testRendersMixedRates(): void
    {
        $command = new SetTransmitRate(true, false, true, false, TransmitPower::Power20);

        self::assertSame('^WRY,N,Y,N,20', (string) $command);
    }
}
