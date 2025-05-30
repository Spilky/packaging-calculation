<?php declare(strict_types = 1);

namespace App\Packing\Result;

use App\Entity\Packaging;
use App\Product\Product;
use App\Product\ProductCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function hash;
use function json_encode;
use const JSON_THROW_ON_ERROR;

#[ORM\Entity]
class PackingResult
{

    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Packaging::class)]
    private Packaging $packaging;

    public function __construct(ProductCollection $products, Packaging $packaging)
    {
        $this->id = self::generateId($products);
        $this->packaging = $packaging;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPackaging(): Packaging
    {
        return $this->packaging;
    }

    public static function generateId(ProductCollection $products): string
    {
        $sortedProducts = $products->sortById();

        $encodedProducts = json_encode(
            $sortedProducts->map(static fn (Product $product): array => [
                'id' => $product->id,
                'width' => $product->width,
                'height' => $product->height,
                'length' => $product->length,
                'weight' => $product->weight,
            ]),
            JSON_THROW_ON_ERROR,
        );

        return hash('sha256', $encodedProducts);
    }

}
