<?php declare(strict_types = 1);

namespace App\Tests\Unit\Product;

use App\Product\Product;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function testConstructorWithValidParameters(): void
    {
        $id = 1;
        $width = 10.0;
        $height = 20.0;
        $length = 15.0;
        $weight = 5.0;

        $product = new Product($id, $width, $height, $length, $weight);

        self::assertSame($id, $product->id);
        self::assertSame($width, $product->dimensions->width);
        self::assertSame($height, $product->dimensions->height);
        self::assertSame($length, $product->dimensions->length);
        self::assertSame($weight, $product->weight);
    }

    public function testConstructorThrowsExceptionForInvalidWeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight must be greater than 0');

        new Product(1, 10.0, 20.0, 15.0, 0.0);
    }

}
