<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\AbortDownloadGraphic;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbortDownloadGraphic::class)]
class AbortDownloadGraphicTest extends UnitTestCase
{
    public function testRendersCommand(): void
    {
        self::assertSame('~DN', (string) new AbortDownloadGraphic());
    }
}
