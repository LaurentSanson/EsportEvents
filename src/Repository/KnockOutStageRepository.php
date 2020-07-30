<?php

namespace App\Repository;

use App\Entity\KnockOutStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KnockOutStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method KnockOutStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method KnockOutStage[]    findAll()
 * @method KnockOutStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnockOutStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KnockOutStage::class);
    }

    // /**
    //  * @return KnockOutStage[] Returns an array of KnockOutStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KnockOutStage
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
