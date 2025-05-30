<?php declare(strict_types = 1);

namespace App\Packing\Exception;

use Exception;
use Throwable;

class PackingUnavailableException extends Exception
{

    public function __construct(Throwable|null $previous = null)
    {
        parent::__construct('Packing unavailable at the moment.', previous: $previous);
    }

}
