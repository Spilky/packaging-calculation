<?php declare(strict_types = 1);

namespace App\Packaging;

use App\Entity\Packaging;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Packing\PackingService;
use App\Product\ProductCollection;

readonly class GetSuitableBoxUseCase
{

    public function __construct(
        private PackagingRepository $packagingRepository,
        private PackingService $packingService,
    )
    {
    }

	/**
	 * @throws PackingUnavailableException
	 * @throws ProductsCanNotBePackedException
	 * @throws PackingAttemptFailedException
	 */
	public function execute(ProductCollection $products): Packaging
    {
        $boxes = $this->packagingRepository->getAll();

        return $this->packingService->pack($products, $boxes);
    }

}
