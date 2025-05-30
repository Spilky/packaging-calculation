<?php declare(strict_types = 1);

namespace App\Packaging;

use App\Entity\Packaging;
use App\Entity\PackagingCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

readonly class DoctrinePackagingRepository implements PackagingRepository
{

    /**
     * @var EntityRepository<Packaging>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $em,
    )
    {
        $this->repository = new EntityRepository($this->em, $this->em->getClassMetadata(Packaging::class));
    }

    public function getAll(): PackagingCollection
    {
        return new PackagingCollection($this->repository->findAll());
    }

}
