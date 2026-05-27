<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ChangeInternationalEncoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ChangeInternationalEncoding::class)]
#[UsesClass(CharacterRemap::class)]
#[UsesClass(Encoding::class)]
#[UsesClass(ValueAssert::class)]
class ChangeInternationalEncodingTest extends UnitTestCase
{
    public function testRendersWithMultipleRemaps(): void
    {
        $command = new ChangeInternationalEncoding(
            Encoding::Utf8,
            new CharacterRemap(65, 66),
            new CharacterRemap(67, 68),
        );

        self::assertSame('^CI28,65,66,67,68', (string) $command);
    }

    public function testRendersWithoutRemaps(): void
    {
        self::assertSame('^CI28', (string) new ChangeInternationalEncoding(Encoding::Utf8));
    }

    public function testRendersWithSingleRemap(): void
    {
        $command = new ChangeInternationalEncoding(
            Encoding::Utf8,
            new CharacterRemap(65, 66),
        );

        self::assertSame('^CI28,65,66', (string) $command);
    }
}
