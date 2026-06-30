<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class DefineEpcDataStructure implements ZplCommand
{
    public const string COMMAND = '^RB';
    public const string FORMAT = '%d';
    public const string FORMAT_WITH_PARTITIONS = '%d,%s';

    /** Largest individual partition size, in bits. */
    public const int MAX_PARTITION_SIZE = 64;

    /** @var int[] */
    private array $partitionSizes;
    private int $totalBitSize;

    public function __construct(
        int $totalBitSize,
        int ...$partitionSizes,
    ) {
        ValueAssert::int($totalBitSize, 1);

        foreach ($partitionSizes as $size) {
            ValueAssert::int($size, 1, self::MAX_PARTITION_SIZE);
        }

        $this->totalBitSize = $totalBitSize;
        $this->partitionSizes = $partitionSizes;
    }

    public function __toString()
    {
        if ($this->partitionSizes === []) {
            return self::COMMAND . sprintf(self::FORMAT, $this->totalBitSize);
        }

        return self::COMMAND . sprintf(
            self::FORMAT_WITH_PARTITIONS,
            $this->totalBitSize,
            implode(',', $this->partitionSizes),
        );
    }
}
