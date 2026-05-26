<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ChangeInternationalEncoding implements ZplCommand
{
    public const string COMMAND = '^CI';
    public const string FORMAT = '%s';
    public const string FORMAT_WITH_REMAPS = '%s,%s';

    /** @var CharacterRemap[] */
    private array $characterRemaps;

    public function __construct(
        private Encoding $encoding,
        CharacterRemap ...$characterRemaps,
    ) {
        $this->characterRemaps = $characterRemaps;
    }

    public function __toString()
    {
        if ($this->characterRemaps === []) {
            return self::COMMAND . sprintf(self::FORMAT, $this->encoding->value);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_REMAPS,
            $this->encoding->value,
            implode(',', $this->characterRemaps),
        );
    }
}
