<?php declare(strict_types = 1);

namespace App\Product;

use App\DataStructure\BaseCollection;

/**
 * @extends BaseCollection<Product>
 */
class ProductCollection extends BaseCollection
{

    protected function getIdentityFunction(): callable
    {
        return static fn (Product $product): int => $product->id;
    }

}
