<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldTypeset;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldTypeset::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class FieldTypesetTest extends UnitTestCase
{
    public function testNegativeXThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldTypeset(-1, 0);
    }

    public function testRendersAtOrigin(): void
    {
        self::assertSame('^FT0,0', (string) new FieldTypeset(0, 0));
    }

    public function testRendersWithCoordinates(): void
    {
        self::assertSame('^FT50,100', (string) new FieldTypeset(50, 100));
    }

    public function testYAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldTypeset(0, 32001);
    }
}
