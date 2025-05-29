<?php declare(strict_types = 1);

namespace App\Packing;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use App\Product\Product;
use App\Product\ProductCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class ApiPackingService implements PackingService
{
    private Client $client;


    public function __construct()
    {
        $this->client = new Client();
    }


    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging
    {
        $body = [
            'username' => 'nakic80613@dlbazi.com',
            'items' => $this->prepareItems($products),
            'bins' => $this->prepareBins($availableBoxes),
        ];

        $request = new Request(
            'POST',
            'https://eu.api.3dbinpacking.com/packer/packIntoMany',
            [],
            json_encode($body, JSON_THROW_ON_ERROR)
        );

        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $e) {

        }

        var_dump($response->getStatusCode());
        var_dump($response->getBody()->getContents());

        return $availableBoxes[0];
    }


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
        ])->getValues();
    }


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
        ])->getValues();
    }
}
