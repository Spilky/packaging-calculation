<?php declare(strict_types = 1);

namespace App\DataStructure;

use App\DataStructure\Exception\InvalidKeyValueTypeException;
use App\DataStructure\Exception\MissingKeyValueException;
use function array_key_exists;
use function is_array;
use function is_float;
use function is_int;
use function is_string;

class ArrayPicker
{

	/**
	 * @param array<string|int, mixed> $data
	 * @throws MissingKeyValueException
	 * @throws InvalidKeyValueTypeException
	 */
	public static function requiredInt(string|int $key, array $data): int
	{
		$value = self::getKeyValue($key, $data);

		if (is_int($value)) {
			return $value;
		}

		throw new InvalidKeyValueTypeException($key, 'int');
	}

    /**
     * @param array<string|int, mixed> $data
     * @throws MissingKeyValueException
     * @throws InvalidKeyValueTypeException
     */
    public static function requiredFloat(string|int $key, array $data): float
    {
        $value = self::getKeyValue($key, $data);

        if (is_float($value)) {
            return $value;
        }

        throw new InvalidKeyValueTypeException($key, 'float');
    }

    /**
     * @param array<string|int, mixed> $data
     * @throws MissingKeyValueException
     * @throws InvalidKeyValueTypeException
     */
    public static function requiredString(string|int $key, array $data): string
    {
        $value = self::getKeyValue($key, $data);

        if (is_string($value)) {
            return $value;
        }

        throw new InvalidKeyValueTypeException($key, 'string');
    }

    /**
     * @param array<string|int, mixed> $data
     * @return array<string|int, mixed>
     * @throws MissingKeyValueException
     * @throws InvalidKeyValueTypeException
     */
    public static function requiredArray(string|int $key, array $data): array
    {
        $value = self::getKeyValue($key, $data);

        if (is_array($value)) {
            return $value;
        }

        throw new InvalidKeyValueTypeException($key, 'array');
    }

	/**
	 * @param array<string|int, mixed> $data
	 * @throws MissingKeyValueException
	 */
	private static function getKeyValue(string|int $key, array $data): mixed
	{
		if ( !array_key_exists($key, $data)) {
			throw new MissingKeyValueException($key);
		}

		return $data[$key];
	}

}
