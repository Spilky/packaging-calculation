<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packaging;

use App\Packaging\Packaging;
use App\Packaging\PackagingCollection;
use App\Packaging\PackagingRepository;

class InMemoryPackagingRepository implements PackagingRepository
{

    /**
     * @var list<Packaging>
     */
    private array $packagings = [];

    public function getAll(): PackagingCollection
    {
        return new PackagingCollection($this->packagings);
    }

    public function add(Packaging $packaging): void
    {
        $this->packagings[] = $packaging;
    }

}
