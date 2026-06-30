<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\MaximumLabelLength;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(MaximumLabelLength::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class MaximumLabelLengthTest extends UnitTestCase
{
    public function testLengthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new MaximumLabelLength(32001);
    }

    public function testLengthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new MaximumLabelLength(-1);
    }

    public function testRendersLength(): void
    {
        self::assertSame('^ML1225', (string) new MaximumLabelLength(1225));
    }
}
