<?php declare(strict_types = 1);

namespace App\Entity\Exception;

use Exception;

class PackagingIdNotSetYetException extends Exception
{

    public function __construct()
    {
        parent::__construct('Packaging id is not set yet.');
    }

}
