<?php declare(strict_types = 1);

namespace App\DataStructure\Exception;

use Exception;

class InvalidKeyValueTypeException extends Exception
{

	public function __construct(string|int $key, string $expectedType)
	{
		parent::__construct("Invalid key `$key` value type. Expected $expectedType.");
	}

}
