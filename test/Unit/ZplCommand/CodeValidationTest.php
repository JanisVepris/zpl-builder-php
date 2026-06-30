<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\CodeValidation;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CodeValidation::class)]
class CodeValidationTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^CVN', (string) new CodeValidation(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^CVY', (string) new CodeValidation(true));
    }
}
