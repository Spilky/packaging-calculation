<?php declare(strict_types = 1);

namespace App\Tests\Unit\Math;

use App\Math\Dimensions;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DimensionsTest extends TestCase
{

    public function testConstructWithValidValues(): void
    {
        $dimensions = new Dimensions(2.0, 3.5, 4.1);
        self::assertSame(2.0, $dimensions->width);
        self::assertSame(3.5, $dimensions->height);
        self::assertSame(4.1, $dimensions->length);
    }

    public function testConstructWithInvalidValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Width must be greater than 0');
        new Dimensions(-1.0, 2.0, 3.0);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Height must be greater than 0');
        new Dimensions(2.0, -1.0, 3.0);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Length must be greater than 0');
        new Dimensions(2.0, 3.0, -1.0);
    }

    #[DataProvider('fitsInProvider')]
    public function testFitsIn(Dimensions $box, Dimensions $targetBox, bool $expected): void
    {
        self::assertSame($expected, $box->fitsIn($targetBox));
    }

    public function testGetPermutations(): void
    {
        $dimensions = new Dimensions(2.0, 3.0, 4.0);
        $expectedPermutations = [
            new Dimensions(2.0, 3.0, 4.0),
            new Dimensions(2.0, 4.0, 3.0),
            new Dimensions(3.0, 2.0, 4.0),
            new Dimensions(3.0, 4.0, 2.0),
            new Dimensions(4.0, 2.0, 3.0),
            new Dimensions(4.0, 3.0, 2.0),
        ];

        self::assertEquals($expectedPermutations, $dimensions->getPermutations());
    }

    /**
     * @return array<string, list<Dimensions|bool>>
     */
    public static function fitsInProvider(): array
    {
        return [
            'fits_in_regular' => [
                new Dimensions(2.0, 2.0, 2.0),
                new Dimensions(3.0, 3.0, 3.0),
                true,
            ],
            'does_not_fit_larger_boxes' => [
                new Dimensions(4.0, 4.0, 4.0),
                new Dimensions(2.0, 2.0, 1.5),
                false,
            ],
            'one_dimension_exceeding' => [
                new Dimensions(3.0, 2.0, 2.0),
                new Dimensions(3.0, 1.5, 2.0),
                false,
            ],
            'fits_in_with_rotation' => [
                new Dimensions(3.0, 2.0, 2.0),
                new Dimensions(2.0, 3.0, 2.0),
                true,
            ],
            'does_not_fit_even_with_rotation' => [
                new Dimensions(5.0, 4.0, 2.0),
                new Dimensions(4.0, 4.0, 3.0),
                false,
            ],
        ];
    }

}
