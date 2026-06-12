<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MemoryLetter;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ChangeMemoryLetters;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ChangeMemoryLetters::class)]
class ChangeMemoryLettersTest extends UnitTestCase
{
    public function testRendersNoneDesignation(): void
    {
        $command = new ChangeMemoryLetters(
            MemoryLetter::MemoryCardB,
            MemoryLetter::Flash,
            MemoryLetter::Ram,
            MemoryLetter::None,
        );

        self::assertSame('^CMB,E,R,NONE', (string) $command);
    }

    public function testRendersReassignedLetters(): void
    {
        $command = new ChangeMemoryLetters(
            MemoryLetter::Flash,
            MemoryLetter::MemoryCardB,
            MemoryLetter::Ram,
            MemoryLetter::MemoryCardA,
        );

        self::assertSame('^CME,B,R,A', (string) $command);
    }
}
