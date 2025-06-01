<?php declare(strict_types = 1);

namespace App\Packaging;

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

    public function sortByVolume(): self
    {
        return $this->sort(
            static fn (Packaging $a, Packaging $b): int => $a->getVolume() <=> $b->getVolume(),
        );
    }

}
