<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Packing\ApiPackingService;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Product\Product;
use App\Product\ProductCollection;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class ApiPackingServiceTest extends TestCase
{

	private ApiPackingService $service;

	private TestClient $httpClient;

	protected function setUp(): void
	{
		$this->httpClient = new TestClient();
		$this->service = new ApiPackingService(
			$this->httpClient,
			'test_user',
			'test_api_key',
		);
	}

	public function testPackSuccessfully(): void
	{
		$products = new ProductCollection([
			new Product(1, 10, 20, 30, 5),
		]);

        $packaging = new class(10, 20, 30, 5) extends Packaging {

            public function getId(): int
            {
                return 1;
            }

        };

		$availableBoxes = new PackagingCollection([
			$packaging,
		]);

		$this->httpClient->setResponse(
			new Response(
				200,
				[],
				json_encode([
					'response' => [
						'status' => 1,
						'bins_packed' => [['bin_data' => ['id' => 1]]],
						'not_packed_items' => [],
					],
				], JSON_THROW_ON_ERROR),
			),
		);

		$result = $this->service->pack($products, $availableBoxes);

		self::assertSame($packaging, $result);
	}

	public function testPackThrowsPackingUnavailableException(): void
	{
		$products = new ProductCollection([]);
		$availableBoxes = new PackagingCollection([]);

		$this->httpClient->throwException(
			new class extends Exception implements ClientExceptionInterface {

			},
		);

		$this->expectException(PackingUnavailableException::class);

		$this->service->pack($products, $availableBoxes);
	}

	public function testPackThrowsPackingAttemptFailedException(): void
	{
		$products = new ProductCollection([]);
		$availableBoxes = new PackagingCollection([]);

		$this->httpClient->setResponse(
			new Response(
				200,
				[],
				json_encode([
					'response' => [
						'status' => 0,
					],
				], JSON_THROW_ON_ERROR),
			),
		);

		$this->expectException(PackingAttemptFailedException::class);

		$this->service->pack($products, $availableBoxes);
	}

	public function testPackThrowsProductsCanNotBePackedException(): void
	{
		$products = new ProductCollection([]);
		$availableBoxes = new PackagingCollection([]);

		$this->httpClient->setResponse(
			new Response(
				200,
				[],
				json_encode([
					'response' => [
						'status' => 1,
						'bins_packed' => [['bin_data' => ['id' => 1]]],
						'not_packed_items' => [['id' => 1]],
					],
				], JSON_THROW_ON_ERROR),
			),
		);

		$this->expectException(ProductsCanNotBePackedException::class);

		$this->service->pack($products, $availableBoxes);
	}

}
