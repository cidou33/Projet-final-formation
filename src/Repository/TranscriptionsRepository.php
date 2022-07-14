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
        //utilisation de queryBuilder afin d'initialiser une requête DQL
        $qb = $this->createQueryBuilder('transcriptions');
        //calibrage de la requête
        $qb
            //selection des informations stockée en BDD dans la table transcriptions
            ->select('transcriptions')
            //selection des informations stockée en BDD dans le champs difficulty.level
            //qui est une clé étrangère se référant à la table difficulty
            ->leftJoin('transcriptions.difficultyLevel', 'difficulty')
            //selection des informations stockée en BDD dans la table difficulty
            ->addSelect('difficulty')
            //
            ->where('difficulty.name LIKE :difficulty')
            ->setParameter('difficulty', $infos);
        $query = $qb
            ->orderBy('transcriptions.bandName', 'ASC')
            ->getQuery();
        $res = $query->getArrayResult();
        return $res;
    }

    public function bandNameSearch($bandNameSearch){
        //utilisation de queryBuilder afin d'initialiser une requête DQL
        $qb = $this->createQueryBuilder('transcriptions');
        //calibrage de la requête
        $qb
            //selection des informations stockées en BDD dans la table transcriptions
            ->select('transcriptions')
            //selection des informations stockée en BDD dans le champs difficulty.level
            //qui est une clé étrangère se référant à la table difficulty
            ->leftJoin('transcriptions.difficultyLevel', 'difficulty')
            //selection des informations stockée en BDD dans la table difficulty
            ->addSelect('difficulty')
            //précision sur les éléments a cibler pour la récupération
            //içi nous ciblons les informations situées dans la table transcription
            //où le champs bandName correspondra à la chaine de caractère affiliée a la clé bandeName
            ->where('transcriptions.bandName LIKE :bandName')
            //paramètrage de la clé bandName: içi elle prendra comme valeur ce qui est stocké dans
            //la variable $bandNameSearch (paramètre de la fonction) qui correspond à l'entrée en input
            //par l'utilisateur
            ->setParameter('bandName', '%'.$bandNameSearch . '%');
        //préparation de la requête et stockage dans une variable en définissant un ordre de récupération
        $query = $qb
            ->orderBy('transcriptions.bandName', 'ASC')
            ->getQuery();
        //récupération des informations sous forme de tableau et renvoie de celui ci en réponse de la fonction
        $res = $query->getArrayResult();
        return $res;
    }
}
