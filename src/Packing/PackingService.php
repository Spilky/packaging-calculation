<?php declare(strict_types = 1);

namespace App\Packing;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Product\ProductCollection;

interface PackingService
{
    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging;
}
