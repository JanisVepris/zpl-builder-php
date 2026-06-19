<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ResetWirelessCard;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ResetWirelessCard::class)]
class ResetWirelessCardTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~WR', (string) new ResetWirelessCard());
    }
}
