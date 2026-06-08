<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\TransferObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(TransferObject::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class TransferObjectTest extends UnitTestCase
{
    public function testDestinationExtensionContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'G:F',
        );
    }

    public function testDestinationExtensionEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: '',
        );
    }

    public function testDestinationExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: str_repeat('G', TransferObject::MAX_EXTENSION_BYTES + 1),
        );
    }

    public function testDestinationNameContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO.1',
            destinationExtension: 'GRF',
        );
    }

    public function testDestinationNameEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: '',
            destinationExtension: 'GRF',
        );
    }

    public function testDestinationNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: str_repeat('A', TransferObject::MAX_NAME_BYTES + 1),
            destinationExtension: 'GRF',
        );
    }

    public function testRendersSpecExample(): void
    {
        self::assertSame(
            '^TOR:ZLOGO.GRF,B:ZLOGO1.GRF',
            (string) new TransferObject(
                sourceDevice: StorageDevice::Ram,
                sourceName: 'ZLOGO',
                sourceExtension: 'GRF',
                destinationDevice: StorageDevice::MemoryCardB,
                destinationName: 'ZLOGO1',
                destinationExtension: 'GRF',
            ),
        );
    }

    public function testRendersWildcardMultipleObjectTransfer(): void
    {
        // The `*` wildcard passes validation (it is not a banned separator) so multiple
        // objects can be transferred in one command, per the spec's `^TOR:LOGO*.GRF,B:NEW*.GRF`.
        self::assertSame(
            '^TOR:LOGO*.GRF,B:NEW*.GRF',
            (string) new TransferObject(
                sourceDevice: StorageDevice::Ram,
                sourceName: 'LOGO*',
                sourceExtension: 'GRF',
                destinationDevice: StorageDevice::MemoryCardB,
                destinationName: 'NEW*',
                destinationExtension: 'GRF',
            ),
        );
    }

    public function testSourceExtensionContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: 'G,F',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }

    public function testSourceExtensionEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: '',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }

    public function testSourceExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO',
            sourceExtension: str_repeat('G', TransferObject::MAX_EXTENSION_BYTES + 1),
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }

    public function testSourceNameContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: 'ZLOGO^1',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }

    public function testSourceNameEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: '',
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }

    public function testSourceNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new TransferObject(
            sourceDevice: StorageDevice::Ram,
            sourceName: str_repeat('A', TransferObject::MAX_NAME_BYTES + 1),
            sourceExtension: 'GRF',
            destinationDevice: StorageDevice::MemoryCardB,
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );
    }
}
