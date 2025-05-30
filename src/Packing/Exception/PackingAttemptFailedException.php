<?php declare(strict_types = 1);

namespace App\Packing\Exception;

use Exception;
use Throwable;
use function sprintf;

class PackingAttemptFailedException extends Exception
{

    public function __construct(string $context, Throwable|null $previous = null)
    {
        parent::__construct(sprintf('Packing attempt failed. Context: %s', $context), previous: $previous);
    }

}
