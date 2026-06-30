<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LeapMode;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetLeapParameters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetLeapParameters::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SetLeapParametersTest extends UnitTestCase
{
    public function testPasswordWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SetLeapParameters(LeapMode::On, 'user', 'pa,ss');
    }

    public function testRendersDisabled(): void
    {
        self::assertSame('^WLOFF,user,secret', (string) new SetLeapParameters(LeapMode::Off, 'user', 'secret'));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^WLON,zebra,secret', (string) new SetLeapParameters(LeapMode::On, 'zebra', 'secret'));
    }

    public function testUsernameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SetLeapParameters(LeapMode::On, str_repeat('a', 41), 'secret');
    }

    public function testUsernameTooShortThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SetLeapParameters(LeapMode::On, 'abc', 'secret');
    }
}
