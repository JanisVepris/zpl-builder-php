<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\CalibrateRfidTransponder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(CalibrateRfidTransponder::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class CalibrateRfidTransponderTest extends UnitTestCase
{
    public function testRendersCustomStrings(): void
    {
        $command = new CalibrateRfidTransponder('begin', 'finish');

        self::assertSame('^HRbegin,finish', (string) $command);
    }

    public function testRendersDefaultStrings(): void
    {
        $command = new CalibrateRfidTransponder('start', 'end');

        self::assertSame('^HRstart,end', (string) $command);
    }

    public function testStartStringTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new CalibrateRfidTransponder(str_repeat('a', 65), 'end');
    }

    public function testStartStringWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new CalibrateRfidTransponder('start,now', 'end');
    }
}
