<?php declare(strict_types = 1);

namespace App\Product;

readonly class Product
{

    public function __construct(
        public int $id,
        public float $width,
        public float $height,
        public float $length,
        public float $weight,
    )
    {
    }

}
