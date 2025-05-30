<?php declare(strict_types = 1);

namespace App\Packaging;

use App\Entity\PackagingCollection;

interface PackagingRepository
{

    public function getAll(): PackagingCollection;

}
