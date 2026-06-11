<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\EraseDownloadGraphics;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EraseDownloadGraphics::class)]
class EraseDownloadGraphicsTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~EG', (string) new EraseDownloadGraphics());
    }
}
