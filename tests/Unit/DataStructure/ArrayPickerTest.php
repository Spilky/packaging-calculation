<?php declare(strict_types = 1);

namespace App\Tests\Unit\DataStructure;

use App\DataStructure\ArrayPicker;
use App\DataStructure\Exception\InvalidKeyValueTypeException;
use App\DataStructure\Exception\MissingKeyValueException;
use PHPUnit\Framework\TestCase;

class ArrayPickerTest extends TestCase
{

	public function testRequiredIntReturnsIntForValidValue(): void
	{
		$data = ['key' => 1];

		$result = ArrayPicker::requiredInt('key', $data);

		self::assertSame(1, $result);
	}

	public function testRequiredIntThrowsExceptionForMissingKey(): void
	{
		$this->expectException(MissingKeyValueException::class);

		ArrayPicker::requiredInt('nonexistent', []);
	}

	public function testRequiredIntThrowsExceptionForInvalidType(): void
	{
		$this->expectException(InvalidKeyValueTypeException::class);

		ArrayPicker::requiredInt('key', ['key' => '123']);
	}

    public function testRequiredFloatReturnsFloatForValidValue(): void
    {
        $data = ['key' => 1.5];

        $result = ArrayPicker::requiredFloat('key', $data);

        self::assertSame(1.5, $result);
    }

    public function testRequiredFloatThrowsExceptionForMissingKey(): void
    {
        $this->expectException(MissingKeyValueException::class);

        ArrayPicker::requiredFloat('nonexistent', []);
    }

    public function testRequiredFloatThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidKeyValueTypeException::class);

        ArrayPicker::requiredFloat('key', ['key' => 1]);
    }

    public function testRequiredStringReturnsStringForValidValue(): void
    {
        $data = ['key' => 'value'];

        $result = ArrayPicker::requiredString('key', $data);

        self::assertSame('value', $result);
    }

    public function testRequiredStringThrowsExceptionForMissingKey(): void
    {
        $this->expectException(MissingKeyValueException::class);

        ArrayPicker::requiredString('nonexistent', []);
    }

    public function testRequiredStringThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidKeyValueTypeException::class);

        ArrayPicker::requiredString('key', ['key' => 1]);
    }

    public function testRequiredArrayReturnsArrayForValidValue(): void
    {
        $expectedArray = ['subkey' => 'value'];
        $data = ['key' => $expectedArray];

        $result = ArrayPicker::requiredArray('key', $data);

        self::assertSame($expectedArray, $result);
    }

    public function testRequiredArrayThrowsExceptionForMissingKey(): void
    {
        $this->expectException(MissingKeyValueException::class);

        ArrayPicker::requiredArray('nonexistent', []);
    }

    public function testRequiredArrayThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidKeyValueTypeException::class);

        ArrayPicker::requiredArray('key', ['key' => 'not an array']);
    }

}
