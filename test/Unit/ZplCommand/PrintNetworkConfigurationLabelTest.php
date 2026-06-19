<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintNetworkConfigurationLabel;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintNetworkConfigurationLabel::class)]
class PrintNetworkConfigurationLabelTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~WL', (string) new PrintNetworkConfigurationLabel());
    }
}
