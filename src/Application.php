<?php declare(strict_types = 1);

namespace App;

use App\DataStructure\ArrayPicker;
use App\DataStructure\Exception\InvalidKeyValueTypeException;
use App\DataStructure\Exception\MissingKeyValueException;
use App\DataStructure\Json;
use App\Packaging\DoctrinePackagingRepository;
use App\Packaging\GetSuitableBoxUseCase;
use App\Packing\ApiPackingService;
use App\Product\Product;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function array_map;

class Application
{

    private EntityManager $entityManager;

    private GetSuitableBoxUseCase $getSuitableBoxUseCase;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->getSuitableBoxUseCase = new GetSuitableBoxUseCase(
            new DoctrinePackagingRepository($entityManager),
            new ApiPackingService(new Client(), 'nakic80613@dlbazi.com', '04223281737e4abdacc7552daf6733ff'),
        );
    }

    /**
     * @throws InvalidKeyValueTypeException
     * @throws MissingKeyValueException
     * @throws ORMException
     * @throws JsonException
     * @throws OptimisticLockException
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $body = $request->getBody()->getContents();

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

        $this->getSuitableBoxUseCase->execute($products);

        $this->entityManager->flush();

        return new Response();
    }

}
