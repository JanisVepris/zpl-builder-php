<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\EndFormat;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EndFormat::class)]
class EndFormatTest extends UnitTestCase
{
    public function testRendersEndFormatCommand(): void
    {
        self::assertSame('^XZ', (string) new EndFormat());
    }
}
