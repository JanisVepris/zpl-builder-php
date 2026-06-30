<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintMethod;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\MediaType;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MediaType::class)]
class MediaTypeTest extends UnitTestCase
{
    public function testRendersDirectThermal(): void
    {
        self::assertSame('^MTD', (string) new MediaType(PrintMethod::DirectThermal));
    }

    public function testRendersThermalTransfer(): void
    {
        self::assertSame('^MTT', (string) new MediaType(PrintMethod::ThermalTransfer));
    }
}
