<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MemoryLetter;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ChangeMemoryLetters implements ZplCommand
{
    public const string COMMAND = '^CM';
    public const string FORMAT = '%s,%s,%s,%s';

    public function __construct(
        private MemoryLetter $aliasForB,
        private MemoryLetter $aliasForE,
        private MemoryLetter $aliasForR,
        private MemoryLetter $aliasForA,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->aliasForB->value,
            $this->aliasForE->value,
            $this->aliasForR->value,
            $this->aliasForA->value,
        );
    }
}
