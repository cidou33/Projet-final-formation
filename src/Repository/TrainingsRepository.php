<?php

namespace App\Repository;

use App\Entity\Trainings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trainings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trainings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trainings[]    findAll()
 * @method Trainings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainings::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Trainings $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Trainings $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Trainings[] Returns an array of Trainings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trainings
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMyTrainings()
    {
        $qb = $this->createQueryBuilder('trainings');
        $qb
            ->select('trainings')
            ->leftJoin('trainings.category', 'category')
            ->addSelect('category');
        $query = $qb
            ->orderBy('category.name', 'ASC')
            ->getQuery();
        $trainings = $query->getArrayResult();
        return $trainings;
    }
}
