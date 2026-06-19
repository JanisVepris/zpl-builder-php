<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintConfigurationLabel;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintConfigurationLabel::class)]
class PrintConfigurationLabelTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~WC', (string) new PrintConfigurationLabel());
    }
}
