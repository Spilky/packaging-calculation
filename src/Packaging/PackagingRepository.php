<?php declare(strict_types = 1);

namespace App\Packaging;

interface PackagingRepository
{

    public function getAll(): PackagingCollection;

}
