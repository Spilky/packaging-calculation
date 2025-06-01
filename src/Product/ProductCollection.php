<?php declare(strict_types = 1);

namespace App\Product;

use App\DataStructure\BaseCollection;
use App\Math\Dimensions;
use function array_reduce;
use function max;

/**
 * @extends BaseCollection<Product>
 */
class ProductCollection extends BaseCollection
{

    protected function getIdentityFunction(): callable
    {
        return static fn (Product $product): int => $product->id;
    }

    public function sortById(): self
    {
        return $this->sort(
            static fn (Product $a, Product $b): int => $a->id <=> $b->id,
        );
    }

    public function sumWeight(): float
    {
        return array_reduce(
            $this->items,
            static fn (float $sum, Product $product): float => $sum + $product->weight,
            0.0,
        );
    }

    public function boundingDimensions(): Dimensions
    {
        $boundingWidth = 0.0;
        $boundingHeight = 0.0;
        $boundingLength = 0.0;

        foreach ($this as $product) {
            $boundingWidth += $product->dimensions->width;
            $boundingHeight = max($boundingHeight, $product->dimensions->height);
            $boundingLength = max($boundingLength, $product->dimensions->length);
        }

        return new Dimensions($boundingWidth, $boundingHeight, $boundingLength);
    }

}
