<?php declare(strict_types = 1);

namespace App\Packing;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Product\ProductCollection;

readonly class BoundingBoxPackingService implements PackingService
{

    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging
    {
        $totalWeight = $products->sumWeight();

        $weightSuitableBoxes = $availableBoxes->filter(
            static fn (Packaging $packaging): bool => $totalWeight <= $packaging->getMaxWeight(),
        );

        if ($weightSuitableBoxes->isEmpty()) {
            throw new ProductsCanNotBePackedException();
        }

        $boundingDimensions = $products->boundingDimensions();
        $boxesByVolume = $weightSuitableBoxes->sortByVolume();

        foreach ($boxesByVolume as $box) {
            if ($boundingDimensions->fitsIn($box->getDimensions())) {
                return $box;
            }
        }

        throw new ProductsCanNotBePackedException();
    }

}
