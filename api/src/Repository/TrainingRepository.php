<?php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends ServiceEntityRepository<Training>
 */
class TrainingRepository extends ServiceEntityRepository
{
    protected ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Training::class);
        $this->validator = $validator;
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
     * create or update one Training
     * /!\ without validating by validator
     * @param Training $training
     * @return void
     */
    public function flushOneTraining(Training $training): void
    {
        $this->getEntityManager()->persist($training);
        $this->getEntityManager()->flush();
    }

    /**
     * validate Training data, return null if the data is correct otherwise the errors
     * @param Training $training
     * @return bool|ConstraintViolationListInterface
     */
    public function validateTraining(Training $training): null|ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($training);
        if ($errors->count() > 0) return $errors;
        return null;
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
