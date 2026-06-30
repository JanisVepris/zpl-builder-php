<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ProtectedMode;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ModeProtection;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ModeProtection::class)]
class ModeProtectionTest extends UnitTestCase
{
    public function testRendersDisableDarkness(): void
    {
        self::assertSame('^MPD', (string) new ModeProtection(ProtectedMode::DisableDarkness));
    }

    public function testRendersDisableSaves(): void
    {
        self::assertSame('^MPS', (string) new ModeProtection(ProtectedMode::DisableSaves));
    }

    public function testRendersEnableAll(): void
    {
        self::assertSame('^MPE', (string) new ModeProtection(ProtectedMode::EnableAll));
    }
}
