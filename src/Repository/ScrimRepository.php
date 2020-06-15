<?php

namespace App\Repository;

use App\Entity\Scrim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scrim|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scrim|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scrim[]    findAll()
 * @method Scrim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScrimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scrim::class);
    }

    // /**
    //  * @return Scrim[] Returns an array of Scrim objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scrim
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
