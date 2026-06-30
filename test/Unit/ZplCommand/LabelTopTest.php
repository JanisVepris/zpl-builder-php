<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\LabelTop;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(LabelTop::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class LabelTopTest extends UnitTestCase
{
    public function testOffsetAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelTop(121);
    }

    public function testOffsetBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelTop(-121);
    }

    public function testRendersNegativeOffset(): void
    {
        self::assertSame('^LT-30', (string) new LabelTop(-30));
    }

    public function testRendersPositiveOffset(): void
    {
        self::assertSame('^LT120', (string) new LabelTop(120));
    }
}
