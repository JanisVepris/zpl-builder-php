<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidLockStyle;
use Janisvepris\ZplBuilder\Enum\RfidPasswordMemoryBank;
use Janisvepris\ZplBuilder\Exception\InvalidHexValueException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetRfidTagPassword;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetRfidTagPassword::class)]
#[UsesClass(InvalidHexValueException::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetRfidTagPasswordTest extends UnitTestCase
{
    public function testNonHexPasswordThrows(): void
    {
        $this->expectException(InvalidHexValueException::class);

        new SetRfidTagPassword('XYZ', null, null);
    }

    public function testPasswordTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SetRfidTagPassword('123456789', null, null);
    }

    public function testRendersPasswordAndMemoryBank(): void
    {
        $command = new SetRfidTagPassword('1234ABCD', RfidPasswordMemoryBank::Epc, null);

        self::assertSame('^RZ1234ABCD,E', (string) $command);
    }

    public function testRendersPasswordBankAndLock(): void
    {
        $command = new SetRfidTagPassword('1234ABCD', RfidPasswordMemoryBank::Epc, RfidLockStyle::Locked);

        self::assertSame('^RZ1234ABCD,E,L', (string) $command);
    }

    public function testRendersPasswordOnly(): void
    {
        $command = new SetRfidTagPassword('5A', null, null);

        self::assertSame('^RZ5A', (string) $command);
    }
}
