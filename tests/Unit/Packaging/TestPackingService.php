<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packaging;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Packing\PackingService;
use App\Product\ProductCollection;

class TestPackingService implements PackingService
{

    private bool $packingUnavailable = false;

    private Packaging|null $packaging = null;

    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging
    {
        if ($this->packingUnavailable) {
            throw new PackingUnavailableException();
        }

        if ($this->packaging !== null) {
            return $this->packaging;
        }

        foreach ($availableBoxes as $box) {
            // Mock simple packing logic where the first box is always chosen
            return $box;
        }

        throw new ProductsCanNotBePackedException();
    }

    public function packingUnavailable(): void
    {
        $this->packingUnavailable = true;
    }

    public function setPackaging(Packaging $packaging): void
    {
        $this->packaging = $packaging;
    }

}
