<?php declare(strict_types = 1);

namespace App\Entity;


use App\DataStructure\BaseCollection;

/**
 * @extends BaseCollection<Packaging>
 */
class PackagingCollection extends BaseCollection
{
    protected function getIdentityFunction(): callable
    {
        return static fn (Packaging $packaging): int => $packaging->getId();
    }

}
