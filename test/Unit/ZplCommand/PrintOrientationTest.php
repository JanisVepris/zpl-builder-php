<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintOrientation as PrintOrientationEnum;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintOrientation;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintOrientation::class)]
class PrintOrientationTest extends UnitTestCase
{
    public function testRendersNormal(): void
    {
        self::assertSame('^PON', (string) new PrintOrientation(PrintOrientationEnum::NORMAL));
    }

    public function testRendersInverted(): void
    {
        self::assertSame('^POI', (string) new PrintOrientation(PrintOrientationEnum::INVERTED));
    }
}
