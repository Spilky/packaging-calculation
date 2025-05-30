<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing\Result;

use App\Packing\Result\PackingResult;
use App\Product\Product;
use App\Product\ProductCollection;
use PHPUnit\Framework\TestCase;

class PackingResultTest extends TestCase
{

	public function testGenerateIdConsistency(): void
	{
		$products = new ProductCollection([
            new Product(2, 15.0, 25.0, 35.0, 45.0),
            new Product(1, 10.0, 20.0, 30.0, 40.0),
		]);

		$productsSorted = $products->sortById();

		$id1 = PackingResult::generateId($products);
		$id2 = PackingResult::generateId($productsSorted);

		self::assertSame($id1, $id2);
	}

	public function testGenerateIdWithDifferentProducts(): void
	{
		$products1 = new ProductCollection([
			new Product(1, 10.0, 20.0, 30.0, 40.0),
			new Product(2, 15.0, 25.0, 35.0, 45.0),
		]);

		$products2 = new ProductCollection([
			new Product(3, 5.0, 10.0, 15.0, 20.0),
			new Product(4, 12.0, 18.0, 24.0, 30.0),
		]);

		$id1 = PackingResult::generateId($products1);
		$id2 = PackingResult::generateId($products2);

		self::assertNotSame($id1, $id2);
	}

}
