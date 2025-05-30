<?php declare(strict_types = 1);

namespace App\DataStructure\Exception;

use Exception;

class MissingKeyValueException extends Exception
{

	public function __construct(string|int $key)
	{
		parent::__construct("`Key` $key is required but missing.");
	}

}
