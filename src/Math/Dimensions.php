<?php declare(strict_types = 1);

namespace App\Math;

use function array_any;

readonly class Dimensions
{

    public function __construct(
        public float $width,
        public float $height,
        public float $length,
    )
    {
    }

    public function fitsIn(self $boxDimensions): bool
    {
        return array_any(
            $boxDimensions->getPermutations(),
            fn (
                $permutation
            ) => $this->width <= $permutation->width &&
                $this->height <= $permutation->height &&
                $this->length <= $permutation->length,
        );
    }

    /**
     * @return list<self>
     */
    public function getPermutations(): array
    {
        return [
            new Dimensions($this->width, $this->height, $this->length),
            new Dimensions($this->width, $this->length, $this->height),
            new Dimensions($this->height, $this->width, $this->length),
            new Dimensions($this->height, $this->length, $this->width),
            new Dimensions($this->length, $this->width, $this->height),
            new Dimensions($this->length, $this->height, $this->width),
        ];
    }

}
