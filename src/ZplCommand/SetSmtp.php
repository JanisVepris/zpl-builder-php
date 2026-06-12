<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetSmtp implements ZplCommand
{
    public const string COMMAND = '^NT';
    public const string FORMAT = '%s,%s';

    private string $domain;
    private string $serverAddress;

    public function __construct(
        string $serverAddress,
        string $domain,
    ) {
        ValueAssert::stringNotContains($serverAddress, ['^', '~', ',']);
        ValueAssert::stringNotContains($domain, ['^', '~', ',']);

        $this->serverAddress = $serverAddress;
        $this->domain = $domain;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->serverAddress,
            $this->domain,
        );
    }
}
