<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\StartFormat;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StartFormat::class)]
class StartFormatTest extends UnitTestCase
{
    public function testRendersStartFormatCommand(): void
    {
        self::assertSame('^XA', (string) new StartFormat());
    }
}
