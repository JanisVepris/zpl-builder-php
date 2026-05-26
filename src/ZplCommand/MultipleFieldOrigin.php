<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\FieldOriginLocation;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class MultipleFieldOrigin implements ZplCommand
{
    public const string COMMAND = '^FM';
    public const string FORMAT = '%s';

    /** Maximum number of (x,y) location pairs accepted by ^FM per the ZPL II Programming Guide. */
    public const int MAX_LOCATIONS = 60;

    /** @var FieldOriginLocation[] */
    private array $locations;

    /** @throws IntegerValueOutOfRangeException */
    public function __construct(FieldOriginLocation ...$locations)
    {
        ValueAssert::int(count($locations), 1, self::MAX_LOCATIONS);

        $this->locations = $locations;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(self::FORMAT, implode(',', $this->locations));
    }
}
