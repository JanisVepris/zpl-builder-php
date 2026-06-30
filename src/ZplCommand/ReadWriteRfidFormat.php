<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidOperation;
use Janisvepris\ZplBuilder\Enum\RfidReadWriteFormat;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class ReadWriteRfidFormat implements ZplCommand
{
    public const string COMMAND = '^RF';
    public const string FORMAT = '%s,%s';
    public const string FORMAT_WITH_BLOCK = '%s,%s,%d';
    public const string FORMAT_WITH_BYTES = '%s,%s,%d,%d';

    private RfidReadWriteFormat $format;
    private ?int $numberOfBytes;
    private RfidOperation $operation;
    private ?int $startingBlock;

    public function __construct(
        RfidOperation $operation,
        RfidReadWriteFormat $format,
        ?int $startingBlock,
        ?int $numberOfBytes,
    ) {
        if ($startingBlock !== null) {
            ValueAssert::int($startingBlock);
        }

        if ($numberOfBytes !== null) {
            ValueAssert::int($numberOfBytes, 1);
        }

        $this->operation = $operation;
        $this->format = $format;
        $this->startingBlock = $startingBlock;
        $this->numberOfBytes = $numberOfBytes;
    }

    public function __toString()
    {
        if ($this->startingBlock === null) {
            return self::COMMAND . sprintf(self::FORMAT, $this->operation->value, $this->format->value);
        }

        if ($this->numberOfBytes === null) {
            return self::COMMAND . sprintf(
                self::FORMAT_WITH_BLOCK,
                $this->operation->value,
                $this->format->value,
                $this->startingBlock,
            );
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_BYTES,
            $this->operation->value,
            $this->format->value,
            $this->startingBlock,
            $this->numberOfBytes,
        );
    }
}
