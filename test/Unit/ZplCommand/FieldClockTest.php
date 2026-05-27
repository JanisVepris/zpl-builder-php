<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldClock::class)]
#[UsesClass(DuplicateClockIndicatorException::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(TertiaryClockIndicatorWithoutSecondaryException::class)]
#[UsesClass(ValueAssert::class)]
class FieldClockTest extends UnitTestCase
{
    public function testCommaInIndicatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldClock(',');
    }

    public function testEmptyIndicatorThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldClock('');
    }

    public function testMultiBytePrimaryThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldClock('ab');
    }

    public function testRendersWithAllThreeIndicators(): void
    {
        self::assertSame('^FC%,{,#', (string) new FieldClock('%', '{', '#'));
    }

    public function testRendersWithPrimaryAndSecondary(): void
    {
        self::assertSame('^FC%,{', (string) new FieldClock('%', '{'));
    }

    public function testRendersWithPrimaryOnly(): void
    {
        self::assertSame('^FC%', (string) new FieldClock('%'));
    }

    public function testSecondaryEqualsPrimaryThrows(): void
    {
        $this->expectException(DuplicateClockIndicatorException::class);

        new FieldClock('%', '%');
    }

    public function testTertiaryCaretThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldClock('%', '{', '^');
    }

    public function testTertiaryEqualsPrimaryThrows(): void
    {
        $this->expectException(DuplicateClockIndicatorException::class);

        new FieldClock('%', '{', '%');
    }

    public function testTertiaryEqualsSecondaryThrows(): void
    {
        $this->expectException(DuplicateClockIndicatorException::class);

        new FieldClock('%', '{', '{');
    }

    public function testTertiaryWithoutSecondaryThrows(): void
    {
        $this->expectException(TertiaryClockIndicatorWithoutSecondaryException::class);

        new FieldClock('%', null, '#');
    }

    public function testTildeInIndicatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldClock('~');
    }
}
