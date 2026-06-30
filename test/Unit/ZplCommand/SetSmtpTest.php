<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetSmtp;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetSmtp::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SetSmtpTest extends UnitTestCase
{
    public function testRendersServerAndDomain(): void
    {
        $command = new SetSmtp('192.168.0.5', 'zebra.com');

        self::assertSame('^NT192.168.0.5,zebra.com', (string) $command);
    }

    public function testServerWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SetSmtp('192.168.0.5^', 'zebra.com');
    }
}
