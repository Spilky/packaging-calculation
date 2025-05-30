<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packaging;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Packaging\Exception\MissingProductsException;
use App\Packaging\GetSuitableBoxUseCase;
use App\Packing\PackingService;
use App\Packing\Result\PackingResult;
use App\Product\Product;
use App\Product\ProductCollection;
use App\Tests\Unit\Packing\Result\InMemoryPackingResultRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GetSuitableBoxUseCaseTest extends TestCase
{

	private InMemoryPackingResultRepository $packingResultRepository;

	private InMemoryPackagingRepository $packagingRepository;

    private GetSuitableBoxUseCase $useCase;

	protected function setUp(): void
	{
		$this->packingResultRepository = new InMemoryPackingResultRepository();
		$this->packagingRepository = new InMemoryPackagingRepository();
		$packingService = new class implements PackingService
        {

            public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging
            {
                foreach ($availableBoxes as $box) {
                    // Mock simple packing logic where the first box is always chosen
                    return $box;
                }

                throw new RuntimeException('No available boxes provided');
            }

        };

		$this->useCase = new GetSuitableBoxUseCase(
			$this->packingResultRepository,
			$this->packagingRepository,
            $packingService,
		);
	}

    public function testExecuteThrowsExceptionWhenProductCollectionIsEmpty(): void
    {
        $this->expectException(MissingProductsException::class);

        $products = new ProductCollection([]);
        $this->useCase->execute($products);
    }

	public function testExecuteReturnsPackagingIfPackingResultExists(): void
	{
		$products = new ProductCollection([new Product(1, 1, 1, 1, 1)]);
		$packaging = new Packaging(10, 10, 10, 20);
		$packingResult = new PackingResult($products, $packaging);

		$this->packingResultRepository->add($packingResult);

		$result = $this->useCase->execute($products);

		self::assertSame($packaging, $result);
	}

    public function testExecutePacksProductsIfPackingResultDoesNotExist(): void
    {
        $products = new ProductCollection([new Product(1, 1, 1, 1, 1)]);
        $packaging = new Packaging(10, 10, 10, 20);
        $this->packagingRepository->add($packaging);

        $result = $this->useCase->execute($products);

        self::assertSame($packaging, $result);

        $savedPackingResult = $this->packingResultRepository->find(PackingResult::generateId($products));
        self::assertNotNull($savedPackingResult);
        self::assertSame($packaging, $savedPackingResult->getPackaging());
    }

}
