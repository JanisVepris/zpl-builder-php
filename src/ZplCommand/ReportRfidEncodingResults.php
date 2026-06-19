<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidReportMode;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ReportRfidEncodingResults implements ZplCommand
{
    public const string COMMAND = '~RV';
    public const string FORMAT = '%s';

    public function __construct(
        private RfidReportMode $mode,
    ) {}

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, $this->mode->value);
    }
}
