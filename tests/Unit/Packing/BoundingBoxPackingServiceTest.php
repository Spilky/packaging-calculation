<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing;

use App\Math\Dimensions;
use App\Packaging\Packaging;
use App\Packaging\PackagingCollection;
use App\Packing\BoundingBoxPackingService;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Product\ProductCollection;
use PHPUnit\Framework\TestCase;

class BoundingBoxPackingServiceTest extends TestCase
{

    private BoundingBoxPackingService $packingService;

    protected function setUp(): void
    {
        $this->packingService = new BoundingBoxPackingService();
    }

    public function testPackThrowsExceptionWhenNoBoxesFitWeight(): void
    {
        $products = $this->createMock(ProductCollection::class);
        $packaging1 = new Packaging(10, 10, 10, 5);  // Max Weight 5
        $packaging2 = new Packaging(15, 15, 15, 10); // Max Weight 10

        $availableBoxes = new PackagingCollection([$packaging1, $packaging2]);

        $products->expects($this->once())
            ->method('sumWeight')
            ->willReturn(15.0);

        $products->expects($this->never())
            ->method('boundingDimensions');

        $this->expectException(ProductsCanNotBePackedException::class);

        $this->packingService->pack($products, $availableBoxes);
    }

    public function testPackThrowsExceptionWhenNoBoxesFitDimensions(): void
    {
        $products = $this->createMock(ProductCollection::class);
        $packaging1 = new Packaging(10, 10, 10, 20); // Max Weight 20
        $packaging2 = new Packaging(15, 15, 15, 30); // Max Weight 30

        $availableBoxes = new PackagingCollection([$packaging1, $packaging2]);

        $products->expects($this->once())
            ->method('sumWeight')
            ->willReturn(15.0);
        $products->expects($this->once())
            ->method('boundingDimensions')
            ->willReturn(new Dimensions(20, 20, 20));

        $this->expectException(ProductsCanNotBePackedException::class);

        $this->packingService->pack($products, $availableBoxes);
    }

    public function testPackSelectsSmallestSuitableBox(): void
    {
        $products = $this->createMock(ProductCollection::class);
        $packaging1 = new Packaging(20, 20, 20, 30); // Volume 8000, Max Weight 30
        $packaging2 = new Packaging(10, 10, 10, 5); // Volume 1000, Max Weight 5
        $packaging3 = new Packaging(10, 10, 10, 30); // Volume 1000, Max Weight 30
        $packaging4 = new Packaging(15, 15, 15, 30); // Volume 3375, Max Weight 30

        $availableBoxes = new PackagingCollection([$packaging1, $packaging2, $packaging3, $packaging4]);

        $products->expects($this->once())
            ->method('sumWeight')
            ->willReturn(10.0);
        $products->expects($this->once())
            ->method('boundingDimensions')
            ->willReturn(new Dimensions(10, 10, 10));

        $result = $this->packingService->pack($products, $availableBoxes);

        self::assertSame($packaging3, $result);
    }

}
