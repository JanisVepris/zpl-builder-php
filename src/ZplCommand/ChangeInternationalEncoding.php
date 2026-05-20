<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\ZplCommand;

class ChangeInternationalEncoding implements ZplCommand
{
    private const string FORMAT = '^CI%s';
    private const string FORMAT_WITH_REMAPS = '^CI%s,%s';

    /** @var CharacterRemap[] */
    private array $characterRemaps = [];

    public function __construct(
        private readonly Encoding $encoding,
        CharacterRemap ...$characterRemaps,
    ) {
        $this->characterRemaps = $characterRemaps;
    }

    public function __toString()
    {
        if ($this->characterRemaps === []) {
            return sprintf(self::FORMAT, $this->encoding->value);
        }

        return sprintf(
            self::FORMAT_WITH_REMAPS,
            $this->encoding->value,
            implode(',', $this->characterRemaps),
        );
    }
}
