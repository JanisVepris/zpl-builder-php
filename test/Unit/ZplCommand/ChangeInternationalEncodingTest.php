<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ChangeInternationalEncoding;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ChangeInternationalEncoding::class)]
class ChangeInternationalEncodingTest extends UnitTestCase
{
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

    public function testRendersWithMultipleRemaps(): void
    {
        $command = new ChangeInternationalEncoding(
            Encoding::Utf8,
            new CharacterRemap(65, 66),
            new CharacterRemap(67, 68),
        );

        self::assertSame('^CI28,65,66,67,68', (string) $command);
    }
}
