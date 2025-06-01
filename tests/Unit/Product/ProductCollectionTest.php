<?php declare(strict_types = 1);

namespace App\Tests\Unit\Product;

use App\Product\Product;
use App\Product\ProductCollection;
use PHPUnit\Framework\TestCase;

class ProductCollectionTest extends TestCase
{

    public function testSortById(): void
    {
        $products = [
            new Product(3, 10.0, 10.0, 10.0, 5.5),
            new Product(2, 15.0, 15.0, 15.0, 3.2),
            new Product(1, 20.0, 20.0, 20.0, 4.8),
        ];

        $collection = new ProductCollection($products);
        $sortedCollection = $collection->sortById();

        $sortedProducts = $sortedCollection->toArray();

        self::assertCount(3, $sortedProducts);
        self::assertSame(1, $sortedProducts[0]->id);
        self::assertSame(2, $sortedProducts[1]->id);
        self::assertSame(3, $sortedProducts[2]->id);
    }

    public function testSumWeight(): void
    {
        $products = [
            new Product(1, 10.0, 10.0, 10.0, 5.5),
            new Product(2, 15.0, 15.0, 15.0, 3.2),
            new Product(3, 20.0, 20.0, 20.0, 4.8),
        ];

        $collection = new ProductCollection($products);
        self::assertSame(13.5, $collection->sumWeight());
    }

    public function testBoundingDimensions(): void
    {
        $products = [
            new Product(1, 10.0, 10.0, 10.0, 5.5),
            new Product(2, 15.0, 20.0, 15.0, 3.2),
            new Product(3, 20.0, 5.0, 25.0, 4.8),
        ];

        $collection = new ProductCollection($products);
        $dimensions = $collection->boundingDimensions();

        self::assertSame(45.0, $dimensions->width);
        self::assertSame(20.0, $dimensions->height);
        self::assertSame(25.0, $dimensions->length);
    }

}
