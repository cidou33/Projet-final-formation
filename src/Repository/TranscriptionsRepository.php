<?php

namespace App\Repository;

use App\Entity\Transcriptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transcriptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transcriptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transcriptions[]    findAll()
 * @method Transcriptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranscriptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transcriptions::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Transcriptions $entity, bool $flush = true): void
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
    public function remove(Transcriptions $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Transcriptions[] Returns an array of Transcriptions objects
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
    public function findOneBySomeField($value): ?Transcriptions
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMySelectionByLetter($infos){
        $qb = $this->createQueryBuilder('transcriptions');
        $qb
            ->select('transcriptions')
            ->where('transcriptions.bandName LIKE :letter')
            ->setParameter('letter', $infos.'%')
            ->leftJoin('transcriptions.difficultyLevel', 'difficulty')
            ->addSelect('difficulty');
        $query = $qb
            ->orderBy('transcriptions.bandName', 'ASC')
            ->getQuery();
        $res = $query->getArrayResult();
        return $res;
    }

    public function findMySelectionByDifficulty($infos){
        $qb = $this->createQueryBuilder('transcriptions');
        $qb
            ->select('transcriptions')
            ->leftJoin('transcriptions.difficultyLevel', 'difficulty')
            ->addSelect('difficulty')
            ->where('difficulty.name LIKE :difficulty')
            ->setParameter('difficulty', $infos);
        $query = $qb
            ->orderBy('transcriptions.bandName', 'ASC')
            ->getQuery();
        $res = $query->getArrayResult();
        return $res;
    }

    public function bandNameSearch($bandNameSearch){
        // alias est égal au nom de notre entité
        $qb = $this->createQueryBuilder('transcriptions');
        $qb
            //selcetionne la table (entité)
            ->select('transcriptions')
            //Je lui joins la table type, je donne l'entité de ma table
            //->leftJoin('task.type', 'type')
            //selectionne la table (entité)
            //->addSelect('type')
            ->leftJoin('transcriptions.difficultyLevel', 'difficulty')
            ->addSelect('difficulty')
            ->where('transcriptions.bandName LIKE :bandName')
                // mettre en clé le nom donné au-dessus
                //entourer le $title de '%' permet de dire qu'on cherche tous les mots qui possèdent le mot de $title
                ->setParameter('bandName', $bandNameSearch . '%');
        $query = $qb
            //ranger par ordre de date décroissant
            ->orderBy('transcriptions.bandName', 'ASC')
            ->getQuery();
        //je récupère le résultat dans ma variable $tasks
        $res = $query->getArrayResult();
        //je retourne ma variable
        return $res;
    }
}
