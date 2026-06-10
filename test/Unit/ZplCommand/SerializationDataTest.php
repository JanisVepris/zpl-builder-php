<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SerializationData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SerializationData::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
#[UsesClass(BoolToStr::class)]
class SerializationDataTest extends UnitTestCase
{
    public function testCommaInIncrementThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationData('0001', '1,0', false);
    }

    public function testCommaInStartValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationData('00,01', '1', false);
    }

    public function testEmptyIncrementThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationData('0001', '', false);
    }

    public function testEmptyStartValueThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationData('', '1', false);
    }

    public function testIncrementTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationData('1', str_repeat('1', 3073), false);
    }

    public function testRendersDecrementWithLeadingZeros(): void
    {
        $command = new SerializationData('0100', '-5', true);

        self::assertSame('^SN0100,-5,Y', (string) $command);
    }

    public function testRendersStartValueIncrementAndSuppressedZeros(): void
    {
        $command = new SerializationData('BL0000', '1', false);

        self::assertSame('^SNBL0000,1,N', (string) $command);
    }

    public function testStartValueTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationData(str_repeat('1', 3073), '1', false);
    }

    public function testTildeInStartValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationData('00~01', '1', false);
    }
}
