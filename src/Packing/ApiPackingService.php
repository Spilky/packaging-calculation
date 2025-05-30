<?php declare(strict_types = 1);

namespace App\Packing;

use App\DataStructure\ArrayPicker;
use App\DataStructure\Json;
use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Product\Product;
use App\Product\ProductCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

readonly class ApiPackingService implements PackingService
{
    public function __construct(
        private ClientInterface $httpClient,
        private string $userName,
        private string $apiKey
    )
    {
    }


    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging
    {
        $body = [
            'username' => $this->userName,
            'api_key' => $this->apiKey,
            'items' => $this->prepareItems($products),
            'bins' => $this->prepareBins($availableBoxes),
        ];

        $request = new Request(
            'POST',
            'https://eu.api.3dbinpacking.com/packer/packIntoMany',
            [],
            json_encode($body, JSON_THROW_ON_ERROR)
        );

        var_dump($request->getBody()->getContents());

        $response = $this->httpClient->sendRequest($request);
        $decodedBody = Json::decode($response->getBody()->getContents());
        $decodedResponse = ArrayPicker::requiredArray('response', $decodedBody);
        $decodedStatus = ArrayPicker::requiredInt('status', $decodedResponse);

        if ($decodedStatus === 1) {
            // Chyba
        }

        var_dump($decodedBody);

        return $availableBoxes->getByIdentity(3);
    }


    /**
     * @return array<array<string, mixed>>
     */
    private function prepareItems(ProductCollection $products): array
    {
        return $products->map(static fn (
            Product $product
        ) => [
            'id' => $product->id,
            'w' => $product->width,
            'h' => $product->height,
            'd' => $product->length,
            'wg' => $product->weight,
            'q' => 1,
        ]);
    }


    /**
     * @return array<array<string, mixed>>
     */
    private function prepareBins(PackagingCollection $boxes): array
    {
        return $boxes->map(static fn (
            Packaging $packaging
        ) => [
            'id' => $packaging->getId(),
            'w' => $packaging->getWidth(),
            'h' => $packaging->getHeight(),
            'd' => $packaging->getLength(),
            'max_wg' => $packaging->getMaxWeight(),
        ]);
    }
}
