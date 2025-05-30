<?php declare(strict_types = 1);

namespace App\Packing\Result;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrinePackingResultRepository implements PackingResultRepository
{

    /**
     * @var EntityRepository<PackingResult>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $em,
    )
    {
        $this->repository = new EntityRepository($this->em, $this->em->getClassMetadata(PackingResult::class));
    }

    public function find(string $id): ?PackingResult
    {
        return $this->repository->find($id);
    }

    public function add(PackingResult $packingResult): void
    {
        $this->em->persist($packingResult);
    }

}
