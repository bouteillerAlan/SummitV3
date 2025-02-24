<?php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Training>
 */
class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    public function findAllPaginated(string $field): Pagerfanta
    {
        // avoid to use unknown field
        $allowedFields = $this->getEntityManager()->getClassMetadata(Training::class)->getFieldNames();
        if (!in_array($field, $allowedFields)) throw new \InvalidArgumentException('Invalid field name');

        $query = $this->createQueryBuilder('training')
            ->orderBy('training.' . $field, 'ASC')
            ->getQuery();

        return (new Pagerfanta(new QueryAdapter($query)))->setMaxPerPage(25);
    }
}
