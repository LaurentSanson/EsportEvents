<?php

namespace App\Repository;

use App\Entity\MatchStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchStyle[]    findAll()
 * @method MatchStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchStyle::class);
    }

    // /**
    //  * @return MatchStyle[] Returns an array of MatchStyle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchStyle
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
