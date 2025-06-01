<?php declare(strict_types = 1);

namespace App\Packing;

use App\Packaging\Packaging;
use App\Packaging\PackagingCollection;
use App\Packing\Exception\PackingAttemptFailedException;
use App\Packing\Exception\PackingUnavailableException;
use App\Packing\Exception\ProductsCanNotBePackedException;
use App\Product\ProductCollection;

interface PackingService
{

    /**
     * @throws PackingUnavailableException
     * @throws ProductsCanNotBePackedException
     * @throws PackingAttemptFailedException
     */
    public function pack(ProductCollection $products, PackagingCollection $availableBoxes): Packaging;

}
