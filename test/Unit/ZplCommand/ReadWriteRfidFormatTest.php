<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidOperation;
use Janisvepris\ZplBuilder\Enum\RfidReadWriteFormat;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ReadWriteRfidFormat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ReadWriteRfidFormat::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ReadWriteRfidFormatTest extends UnitTestCase
{
    public function testRendersOperationAndFormat(): void
    {
        $command = new ReadWriteRfidFormat(RfidOperation::Write, RfidReadWriteFormat::Hexadecimal, null, null);

        self::assertSame('^RFW,H', (string) $command);
    }

    public function testRendersWithBlock(): void
    {
        $command = new ReadWriteRfidFormat(RfidOperation::Read, RfidReadWriteFormat::Ascii, 3, null);

        self::assertSame('^RFR,A,3', (string) $command);
    }

    public function testRendersWithBytes(): void
    {
        $command = new ReadWriteRfidFormat(RfidOperation::Write, RfidReadWriteFormat::Hexadecimal, 1, 32);

        self::assertSame('^RFW,H,1,32', (string) $command);
    }

    public function testStartingBlockBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ReadWriteRfidFormat(RfidOperation::Write, RfidReadWriteFormat::Hexadecimal, -1, null);
    }
}
