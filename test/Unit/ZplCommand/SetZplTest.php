<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ZplMode;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SetZpl;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SetZpl::class)]
class SetZplTest extends UnitTestCase
{
    public function testRendersZpl(): void
    {
        self::assertSame('^SZ1', (string) new SetZpl(ZplMode::Zpl));
    }

    public function testRendersZplII(): void
    {
        self::assertSame('^SZ2', (string) new SetZpl(ZplMode::ZplII));
    }
}
