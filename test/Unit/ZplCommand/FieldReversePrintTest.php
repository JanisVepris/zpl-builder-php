<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\FieldReversePrint;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldReversePrint::class)]
class FieldReversePrintTest extends UnitTestCase
{
    public function testRendersFieldReversePrintCommand(): void
    {
        self::assertSame('^FR', (string) new FieldReversePrint());
    }
}
