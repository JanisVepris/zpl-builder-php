<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintStart;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintStart::class)]
class PrintStartTest extends UnitTestCase
{
    public function testRendersPrintStart(): void
    {
        self::assertSame('~PS', (string) new PrintStart());
    }
}
