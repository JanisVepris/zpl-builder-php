<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\NetworkDevice;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrimaryDevice;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrimaryDevice::class)]
class PrimaryDeviceTest extends UnitTestCase
{
    public function testRendersPrinter(): void
    {
        self::assertSame('^NPP', (string) new PrimaryDevice(NetworkDevice::Printer));
    }

    public function testRendersPrintServer(): void
    {
        self::assertSame('^NPM', (string) new PrimaryDevice(NetworkDevice::PrintServer));
    }
}
