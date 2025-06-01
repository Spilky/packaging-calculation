<?php declare(strict_types = 1);

namespace App\Packing;

use App\DataStructure\ArrayPicker;
use App\DataStructure\Exception\InvalidKeyValueTypeException;
use App\DataStructure\Exception\ItemNotFoundByIdentity;
use App\DataStructure\Exception\MissingKeyValueException;
use App\DataStructure\Json;
use App\Packaging\Packaging;
use App\Packaging\PackagingCollection;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Product\Product;
use App\Product\ProductCollection;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use function count;
use function json_encode;
use const JSON_THROW_ON_ERROR;

readonly class ApiPackingService implements PackingService
{

    public function __construct(
        private ClientInterface $httpClient,
        private string $userName,
        private string $apiKey
    )
    {
    }

    /**
     * @throws PackingUnavailableException
     * @throws InvalidKeyValueTypeException
     * @throws MissingKeyValueException
     * @throws ProductsCanNotBePackedException
     * @throws ItemNotFoundByIdentity
     * @throws JsonException
     * @throws PackingAttemptFailedException
     */
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
            json_encode($body, JSON_THROW_ON_ERROR),
        );

        try {
            $httpResponse = $this->httpClient->send($request);
        } catch (ClientExceptionInterface $e) {
            throw new PackingUnavailableException($e);
        }

        $body = $httpResponse->getBody()->getContents();
        $decodedBody = Json::decode($body);
        $response = ArrayPicker::requiredArray('response', $decodedBody);
        $status = ArrayPicker::requiredInt('status', $response);

        if ($status !== 1) {
            throw new PackingAttemptFailedException($body);
        }

        /** @var list<array<string, mixed>> $binsPacked */
        $binsPacked = ArrayPicker::requiredArray('bins_packed', $response);
        $notPackedItems = ArrayPicker::requiredArray('not_packed_items', $response);

        if (count($binsPacked) !== 1 || count($notPackedItems) > 0) {
            throw new ProductsCanNotBePackedException();
        }

        $binData = ArrayPicker::requiredArray('bin_data', $binsPacked[0]);
        $packagingId = ArrayPicker::requiredInt('id', $binData);

        return $availableBoxes->getByIdentity($packagingId);
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
            'vr' => 1,
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
