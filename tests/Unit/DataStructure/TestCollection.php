<?php declare(strict_types = 1);

namespace App\Tests\Unit\DataStructure;

use App\DataStructure\BaseCollection;

/**
 * @extends BaseCollection<int>
 */
class TestCollection extends BaseCollection
{

    protected function getIdentityFunction(): callable
    {
        return static fn (int $item): int => $item;
    }

}
