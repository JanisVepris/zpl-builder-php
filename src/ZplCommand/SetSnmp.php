<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetSnmp implements ZplCommand
{
    public const string COMMAND = '^NN';
    public const string FORMAT = '%s,%s,%s,%s,%s,%s';

    /** Maximum byte length for the get community name. */
    public const int MAX_GET_COMMUNITY_BYTES = 19;

    /** Maximum byte length for the set community name. */
    public const int MAX_SET_COMMUNITY_BYTES = 19;

    /** Maximum byte length for the system contact. */
    public const int MAX_SYSTEM_CONTACT_BYTES = 50;

    /** Maximum byte length for the system location. */
    public const int MAX_SYSTEM_LOCATION_BYTES = 50;

    /** Maximum byte length for the system name. */
    public const int MAX_SYSTEM_NAME_BYTES = 17;

    /** Maximum byte length for the trap community name. */
    public const int MAX_TRAP_COMMUNITY_BYTES = 20;

    private string $getCommunity;
    private string $setCommunity;
    private string $systemContact;
    private string $systemLocation;
    private string $systemName;
    private string $trapCommunity;

    public function __construct(
        string $systemName,
        string $systemContact,
        string $systemLocation,
        string $getCommunity,
        string $setCommunity,
        string $trapCommunity,
    ) {
        self::assertField($systemName, self::MAX_SYSTEM_NAME_BYTES);
        self::assertField($systemContact, self::MAX_SYSTEM_CONTACT_BYTES);
        self::assertField($systemLocation, self::MAX_SYSTEM_LOCATION_BYTES);
        self::assertField($getCommunity, self::MAX_GET_COMMUNITY_BYTES);
        self::assertField($setCommunity, self::MAX_SET_COMMUNITY_BYTES);
        self::assertField($trapCommunity, self::MAX_TRAP_COMMUNITY_BYTES);

        $this->systemName = $systemName;
        $this->systemContact = $systemContact;
        $this->systemLocation = $systemLocation;
        $this->getCommunity = $getCommunity;
        $this->setCommunity = $setCommunity;
        $this->trapCommunity = $trapCommunity;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            $this->systemName,
            $this->systemContact,
            $this->systemLocation,
            $this->getCommunity,
            $this->setCommunity,
            $this->trapCommunity,
        );
    }

    private static function assertField(string $value, int $maxBytes): void
    {
        ValueAssert::stringLengthBytes($value, 0, $maxBytes);
        ValueAssert::stringNotContains($value, ['^', '~', ',']);
    }
}
