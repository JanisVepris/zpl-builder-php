<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetSnmp;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetSnmp::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SetSnmpTest extends UnitTestCase
{
    public function testRendersAllParameters(): void
    {
        $command = new SetSnmp('printer1', 'admin', 'warehouse', 'getc', 'setc', 'trapc');

        self::assertSame('^NNprinter1,admin,warehouse,getc,setc,trapc', (string) $command);
    }

    public function testRendersWithDefaultCommunities(): void
    {
        $command = new SetSnmp('', '', '', 'public', 'public', 'public');

        self::assertSame('^NN,,,public,public,public', (string) $command);
    }

    public function testSystemNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SetSnmp(str_repeat('a', 18), '', '', 'public', 'public', 'public');
    }

    public function testTrapCommunityWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SetSnmp('', '', '', 'public', 'public', 'trap,community');
    }
}
