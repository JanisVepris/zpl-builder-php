<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PostPrintAction;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintMode;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintMode::class)]
class PrintModeTest extends UnitTestCase
{
    public function testRendersCutterWithoutPrepeel(): void
    {
        $command = new PrintMode(PostPrintAction::Cutter, false);

        self::assertSame('^MMC,N', (string) $command);
    }

    public function testRendersTearOffWithPrepeel(): void
    {
        $command = new PrintMode(PostPrintAction::TearOff, true);

        self::assertSame('^MMT,Y', (string) $command);
    }
}
