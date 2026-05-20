<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\LabelReversePrint;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LabelReversePrint::class)]
class LabelReversePrintTest extends UnitTestCase
{
    public function testRendersEnabled(): void
    {
        self::assertSame('^LRY', (string) new LabelReversePrint(true));
    }

    public function testRendersDisabled(): void
    {
        self::assertSame('^LRN', (string) new LabelReversePrint(false));
    }
}
