<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldOrigin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldOrigin::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class FieldOriginTest extends UnitTestCase
{
    public function testNegativeXThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldOrigin(-1, 0);
    }

    public function testRendersAtOrigin(): void
    {
        self::assertSame('^FO0,0', (string) new FieldOrigin(0, 0));
    }

    public function testRendersWithCoordinates(): void
    {
        self::assertSame('^FO50,100', (string) new FieldOrigin(50, 100));
    }

    public function testYAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FieldOrigin(0, 32001);
    }
}
