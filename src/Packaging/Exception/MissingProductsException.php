<?php declare(strict_types = 1);

namespace App\Packaging\Exception;

use Exception;

class MissingProductsException extends Exception
{

    public function __construct()
    {
        parent::__construct('There are no products for packing.');
    }

}
