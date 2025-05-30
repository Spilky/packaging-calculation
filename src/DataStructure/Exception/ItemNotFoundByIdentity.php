<?php declare(strict_types = 1);

namespace App\DataStructure\Exception;

use Exception;
use function sprintf;

class ItemNotFoundByIdentity extends Exception
{

    public function __construct(int $identity)
    {
        parent::__construct(sprintf('Item not found by identity=%d', $identity));
    }

}
