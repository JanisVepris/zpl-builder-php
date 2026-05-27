<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand\LabelReversePrint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(LabelReversePrint::class)]
#[UsesClass(BoolToStr::class)]
class LabelReversePrintTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^LRN', (string) new LabelReversePrint(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^LRY', (string) new LabelReversePrint(true));
    }
}
