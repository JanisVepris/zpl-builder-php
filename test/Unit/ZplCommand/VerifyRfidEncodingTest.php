<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand\VerifyRfidEncoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(VerifyRfidEncoding::class)]
#[UsesClass(BoolToStr::class)]
class VerifyRfidEncodingTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^WVN', (string) new VerifyRfidEncoding(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^WVY', (string) new VerifyRfidEncoding(true));
    }
}
