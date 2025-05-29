<?php declare(strict_types = 1);

namespace App\Packaging;

use App\Entity\Packaging;
use App\Packing\PackingService;
use App\Product\Product;
use App\Product\ProductCollection;

readonly class GetSuitableBoxUseCase
{
    public function __construct(
        private PackagingRepository $packagingRepository,
        private PackingService $packingService,
    )
    {
    }


    public function execute(ProductCollection $products): Packaging
    {
        $boxes = $this->packagingRepository->getAll();

        return $this->packingService->pack($products, $boxes);
    }
}
