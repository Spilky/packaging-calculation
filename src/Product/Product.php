<?php declare(strict_types = 1);

namespace App\Product;

use App\Math\Dimensions;
use InvalidArgumentException;

readonly class Product
{

    public Dimensions $dimensions;

    public function __construct(
        public int $id,
        float $width,
        float $height,
        float $length,
        public float $weight,
    )
    {
        $this->dimensions = new Dimensions($width, $height, $length);

        if ($this->weight <= 0.0) {
            throw new InvalidArgumentException('Weight must be greater than 0');
        }
    }

}
