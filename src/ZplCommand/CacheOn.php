<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\CacheType;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class CacheOn implements ZplCommand
{
    public const string COMMAND = '^CO';
    public const string FORMAT = '%s,%d,%s';

    private int $additionalMemory;
    private bool $enabled;
    private CacheType $type;

    public function __construct(
        bool $enabled,
        int $additionalMemory,
        CacheType $type,
    ) {
        ValueAssert::int($additionalMemory);

        $this->enabled = $enabled;
        $this->additionalMemory = $additionalMemory;
        $this->type = $type;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            BoolToStr::conv($this->enabled),
            $this->additionalMemory,
            $this->type->value,
        );
    }
}
