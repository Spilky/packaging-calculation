<?php declare(strict_types = 1);

namespace App\Packing\Result;

interface PackingResultRepository
{

    public function find(string $id): ?PackingResult;

    public function add(PackingResult $packingResult): void;

}
