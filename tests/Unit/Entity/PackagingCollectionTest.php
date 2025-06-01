<?php declare(strict_types = 1);

namespace App\Tests\Unit\Entity;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use PHPUnit\Framework\TestCase;

class PackagingCollectionTest extends TestCase
{

    public function testSortByVolume(): void
    {
        $packaging1 = new Packaging(2.0, 3.0, 4.0, 10); // Volume = 24.0
        $packaging2 = new Packaging(1.0, 2.0, 1.5, 10); // Volume = 3.0
        $packaging3 = new Packaging(3.0, 3.0, 3.0, 10); // Volume = 27.0

        $collection = new PackagingCollection([$packaging1, $packaging2, $packaging3]);
        $sortedCollection = $collection->sortByVolume();

        $sortedItems = $sortedCollection->toArray();
        self::assertSame($packaging2, $sortedItems[0]);
        self::assertSame($packaging1, $sortedItems[1]);
        self::assertSame($packaging3, $sortedItems[2]);
    }

}
