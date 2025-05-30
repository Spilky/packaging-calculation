<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing\Result;

use App\Packing\Result\PackingResult;
use App\Packing\Result\PackingResultRepository;

class InMemoryPackingResultRepository implements PackingResultRepository
{

    /**
     * @var array<string, PackingResult>
     */
    private array $results = [];

    public function find(string $id): ?PackingResult
    {
        return $this->results[$id] ?? null;
    }

    public function add(PackingResult $packingResult): void
    {
        $this->results[$packingResult->getId()] = $packingResult;
    }

}
