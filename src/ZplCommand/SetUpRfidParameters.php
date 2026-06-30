<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\ApplicatorSignal;
use Janisvepris\ZplBuilder\Enum\PrintSpeed;
use Janisvepris\ZplBuilder\Enum\RfidErrorHandling;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetUpRfidParameters implements ZplCommand
{
    public const string COMMAND = '^RS';
    public const string FORMAT = '%s';

    /** Highest number of labels attempted on read/encode failure. */
    public const int MAX_NUMBER_OF_LABELS = 10;

    /** Highest tag-type code the command accepts. */
    public const int MAX_TAG_TYPE = 9;

    private ?ApplicatorSignal $applicatorSignal;
    private ?RfidErrorHandling $errorHandling;
    private ?int $numberOfLabels;
    private ?int $position;
    private ?int $tagType;
    private ?int $voidLength;
    private ?PrintSpeed $voidPrintSpeed;

    public function __construct(
        ?int $tagType,
        ?int $position,
        ?int $voidLength,
        ?int $numberOfLabels,
        ?RfidErrorHandling $errorHandling,
        ?ApplicatorSignal $applicatorSignal,
        ?PrintSpeed $voidPrintSpeed,
    ) {
        if ($tagType !== null) {
            ValueAssert::int($tagType, 0, self::MAX_TAG_TYPE);
        }

        if ($position !== null) {
            ValueAssert::int($position);
        }

        if ($voidLength !== null) {
            ValueAssert::int($voidLength);
        }

        if ($numberOfLabels !== null) {
            ValueAssert::int($numberOfLabels, 1, self::MAX_NUMBER_OF_LABELS);
        }

        $this->tagType = $tagType;
        $this->position = $position;
        $this->voidLength = $voidLength;
        $this->numberOfLabels = $numberOfLabels;
        $this->errorHandling = $errorHandling;
        $this->applicatorSignal = $applicatorSignal;
        $this->voidPrintSpeed = $voidPrintSpeed;
    }

    public function __toString()
    {
        $optional = [
            $this->tagType === null ? null : (string) $this->tagType,
            $this->position === null ? null : (string) $this->position,
            $this->voidLength === null ? null : (string) $this->voidLength,
            $this->numberOfLabels === null ? null : (string) $this->numberOfLabels,
            $this->errorHandling?->value,
            $this->applicatorSignal?->value,
            null, // certify tag with a pre-read: not applicable
            $this->voidPrintSpeed?->value,
        ];

        $lastSet = -1;
        foreach ($optional as $index => $value) {
            if ($value !== null) {
                $lastSet = $index;
            }
        }

        $parts = [];
        for ($i = 0; $i <= $lastSet; ++$i) {
            $parts[] = $optional[$i] ?? '';
        }

        return self::COMMAND . implode(',', $parts);
    }
}
