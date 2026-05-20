<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\FieldSeparator;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldSeparator::class)]
class FieldSeparatorTest extends UnitTestCase
{
    public function testRendersFieldSeparatorCommand(): void
    {
        self::assertSame('^FS', (string) new FieldSeparator());
    }
}
