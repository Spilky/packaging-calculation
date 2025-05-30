<?php declare(strict_types = 1);

namespace App\Packing\Exception;

use Exception;

class ProductsCanNotBePackedException extends Exception
{

    public function __construct()
    {
        parent::__construct('Products can not be packed into single box.');
    }

}
