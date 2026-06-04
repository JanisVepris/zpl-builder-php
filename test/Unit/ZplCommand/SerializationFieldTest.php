<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SerializationField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SerializationField::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SerializationFieldTest extends UnitTestCase
{
    public function testCombinedLengthAboveMaxThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        // Each string is within the 3072-byte cap on its own, but combined exceeds it.
        new SerializationField(str_repeat('D', 2000), str_repeat('1', 2000));
    }

    public function testCommaInIncrementThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationField('DDDD', '1,0');
    }

    public function testCommaInMaskThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationField('DD,DD', '1');
    }

    public function testEmptyIncrementThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationField('DDDD', '');
    }

    public function testEmptyMaskThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationField('', '1');
    }

    public function testIncrementTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationField('D', str_repeat('1', 3073));
    }

    public function testMaskTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SerializationField(str_repeat('D', 3073), '1');
    }

    public function testRendersMaskAndIncrement(): void
    {
        $command = new SerializationField('%%%%%%%%%%%n', '1');

        self::assertSame('^SF%%%%%%%%%%%n,1', (string) $command);
    }

    public function testRendersMixedMaskWithMultiCharIncrement(): void
    {
        $command = new SerializationField('nnnnA', '11111');

        self::assertSame('^SFnnnnA,11111', (string) $command);
    }

    public function testTildeInMaskThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SerializationField('DD~DD', '1');
    }
}
