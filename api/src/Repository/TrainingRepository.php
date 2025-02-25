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

    /**
     * get the full list of Training
     * paginated via Pagerfanta, 25 items per page
     * @param string $field
     * @return Pagerfanta
     */
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

    /**
     * create one Training
     * @param Training $training
     * @return void
     */
    public function createOneTraining(Training $training): void
    {
        // todo: implement this
    }

    /**
     * delete one Training
     * @param Training $training
     * @return void
     */
    public function deleteOneTraining(Training $training): void
    {
        $this->getEntityManager()->remove($training);
        $this->getEntityManager()->flush();
    }
}
