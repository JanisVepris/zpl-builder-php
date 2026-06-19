<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SetConnectedPrinterTransparent;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SetConnectedPrinterTransparent::class)]
class SetConnectedPrinterTransparentTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~NT', (string) new SetConnectedPrinterTransparent());
    }
}
