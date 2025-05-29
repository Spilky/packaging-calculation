<?php declare(strict_types = 1);

namespace App;

use App\Packaging\DoctrinePackagingRepository;
use App\Packaging\GetSuitableBoxUseCase;
use App\Packing\ApiPackingService;
use App\Product\Product;
use App\Product\ProductCollection;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{

    private EntityManager $entityManager;

    private GetSuitableBoxUseCase $getSuitableBoxUseCase;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->getSuitableBoxUseCase = new GetSuitableBoxUseCase(
            new DoctrinePackagingRepository($entityManager),
            new ApiPackingService(),
        );
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        $body = $request->getBody()->getContents();

        $decodedBody = json_decode($body, true, flags: JSON_THROW_ON_ERROR);

        $products = new ProductCollection(
            array_map(static fn (array $product) => new Product(
                $product['id'],
                $product['width'],
                $product['height'],
                $product['length'],
                $product['weight'],
            ), $decodedBody['products'])
        );

        $box = $this->getSuitableBoxUseCase->execute($products);

        $this->entityManager->flush();

        return new Response();
    }

}
