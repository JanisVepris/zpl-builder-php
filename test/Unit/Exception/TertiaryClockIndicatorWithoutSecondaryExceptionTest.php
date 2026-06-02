<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\Exception;

use InvalidArgumentException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TertiaryClockIndicatorWithoutSecondaryException::class)]
class TertiaryClockIndicatorWithoutSecondaryExceptionTest extends UnitTestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        self::assertInstanceOf(InvalidArgumentException::class, new TertiaryClockIndicatorWithoutSecondaryException('T'));
    }

    public function testMessageIncludesTertiaryIndicator(): void
    {
        $exception = new TertiaryClockIndicatorWithoutSecondaryException('T');

        self::assertSame(
            'Cannot set tertiary clock indicator "T" without also providing a secondary indicator; ^FC parameters are positional.',
            $exception->getMessage(),
        );
    }
}
