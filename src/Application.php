<?php declare(strict_types = 1);

namespace App;

use App\DataStructure\ArrayPicker;
use App\DataStructure\Exception\InvalidKeyValueTypeException;
use App\DataStructure\Exception\MissingKeyValueException;
use App\DataStructure\Json;
use App\Entity\Exception\PackagingIdNotSetYetException;
use App\Http\JsonResponse;
use App\Packaging\DoctrinePackagingRepository;
use App\Packaging\Exception\MissingProductsException;
use App\Packaging\GetSuitableBoxUseCase;
use App\Packing\ApiPackingService;
use App\Packing\BoundingBoxPackingService;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Packing\Result\DoctrinePackingResultRepository;
use App\Product\Product;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use GuzzleHttp\Client;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function array_map;
use function sprintf;

class Application
{

    private EntityManager $entityManager;

    private GetSuitableBoxUseCase $getSuitableBoxUseCase;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->getSuitableBoxUseCase = new GetSuitableBoxUseCase(
            new DoctrinePackingResultRepository($entityManager),
            new DoctrinePackagingRepository($entityManager),
            new ApiPackingService(
                new Client(),
                ArrayPicker::requiredString('API_USERNAME', $_ENV),
                ArrayPicker::requiredString('API_KEY', $_ENV),
            ),
            new BoundingBoxPackingService(),
        );
    }

    /**
     * @throws JsonException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PackagingIdNotSetYetException
     * @throws PackingAttemptFailedException
     * @throws PackingUnavailableException
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $body = $request->getBody()->getContents();

        try {
            $decodedBody = Json::decode($body);

            /** @var list<array<string, mixed>> $decodedProducts */
            $decodedProducts = ArrayPicker::requiredArray('products', $decodedBody);

            $products = new ProductCollection(
                array_map(static fn (array $productData): Product => new Product(
                    ArrayPicker::requiredInt('id', $productData),
                    ArrayPicker::requiredFloat('width', $productData),
                    ArrayPicker::requiredFloat('height', $productData),
                    ArrayPicker::requiredFloat('length', $productData),
                    ArrayPicker::requiredFloat('weight', $productData),
                ), $decodedProducts),
            );
        } catch (JsonException | InvalidKeyValueTypeException | MissingKeyValueException $e) {
            return JsonResponse::createError(sprintf('Invalid request: %s', $e->getMessage()));
        }

        try {
            $packaging = $this->getSuitableBoxUseCase->execute($products);
        } catch (MissingProductsException | ProductsCanNotBePackedException $e) {
            return JsonResponse::createError($e->getMessage());
        }

        $this->entityManager->flush();

        return JsonResponse::createOk(['packagingId' => $packaging->getId()]);
    }

}
