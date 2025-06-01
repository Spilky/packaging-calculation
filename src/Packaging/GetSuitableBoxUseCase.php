<?php declare(strict_types = 1);

namespace App\Packaging;

use App\Packaging\Exception\MissingProductsException;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Packing\PackingService;
use App\Packing\Result\PackingResult;
use App\Packing\Result\PackingResultRepository;
use App\Product\ProductCollection;
use JsonException;

readonly class GetSuitableBoxUseCase
{

    public function __construct(
		private PackingResultRepository $packingResultRepository,
        private PackagingRepository $packagingRepository,
        private PackingService $primaryPackingService,
        private PackingService $fallbackPackingService,
    )
    {
    }

	/**
	 * @throws PackingUnavailableException
	 * @throws ProductsCanNotBePackedException
	 * @throws PackingAttemptFailedException
	 * @throws JsonException
	 * @throws MissingProductsException
	 */
	public function execute(ProductCollection $products): Packaging
    {
		if ($products->isEmpty()) {
			throw new MissingProductsException();
		}

		$packingResult = $this->packingResultRepository->find(PackingResult::generateId($products));

		if ($packingResult !== null) {
			return $packingResult->getPackaging();
		}

        $availableBoxes = $this->packagingRepository->getAll();

        try {
            $packaging = $this->primaryPackingService->pack($products, $availableBoxes);
        } catch (PackingUnavailableException) {
            return $this->fallbackPackingService->pack($products, $availableBoxes);
        }

        $this->packingResultRepository->add(new PackingResult($products, $packaging));

		return $packaging;
    }

}
