<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintOrientation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PrintOrientation::class)]
#[UsesClass(LabelFlip::class)]
class PrintOrientationTest extends UnitTestCase
{
    public function testRendersInverted(): void
    {
        self::assertSame('^POI', (string) new PrintOrientation(LabelFlip::Inverted));
    }

    public function testRendersNormal(): void
    {
        self::assertSame('^PON', (string) new PrintOrientation(LabelFlip::Normal));
    }
}
