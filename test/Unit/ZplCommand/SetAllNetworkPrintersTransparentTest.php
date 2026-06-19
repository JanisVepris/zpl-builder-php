<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SetAllNetworkPrintersTransparent;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SetAllNetworkPrintersTransparent::class)]
class SetAllNetworkPrintersTransparentTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~NR', (string) new SetAllNetworkPrintersTransparent());
    }
}
