<?php declare(strict_types = 1);

namespace App\Tests\Unit\DataStructure;

use App\DataStructure\Exception\ItemNotFoundByIdentity;
use PHPUnit\Framework\TestCase;

class BaseCollectionTest extends TestCase
{

    public function testCollectionCanBeIterated(): void
    {
        $collection = new TestCollection([100, 200, 300]);

        $counter = 0;

        foreach ($collection as $item) {
            $counter++;
        }

        self::assertSame(3, $counter);
    }

    public function testCountItems(): void
    {
        $collection = new TestCollection([100, 200, 300]);

        self::assertSame(3, $collection->count());
        self::assertCount(3, $collection);
    }

    public function testIsEmpty(): void
    {
        $emptyCollection = new TestCollection([]);
        $notEmptyCollection = new TestCollection([100, 200, 300]);

        self::assertTrue($emptyCollection->isEmpty());
        self::assertFalse($notEmptyCollection->isEmpty());
    }

    public function testConvertToArray(): void
    {
        $collection = new TestCollection([100, 200, 300]);
        $expected = [100, 200, 300];

        self::assertSame($expected, $collection->toArray());
    }

    public function testMap(): void
    {
        $collection = new TestCollection([100, 200, 300]);

        $expected = ['100-suffix', '200-suffix', '300-suffix'];

        self::assertSame($expected, $collection->map(static fn (int $item) => $item . '-suffix'));
    }

    public function testGetByIdentity(): void
    {
        $collection = new TestCollection([100, 200, 300]);

        self::assertSame(100, $collection->getByIdentity(100));

        $this->expectException(ItemNotFoundByIdentity::class);

        $collection->getByIdentity(900);
    }

}
