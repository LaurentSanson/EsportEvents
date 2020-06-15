<?php

namespace App\Repository;

use App\Entity\TournamentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TournamentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentType[]    findAll()
 * @method TournamentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentType::class);
    }

    // /**
    //  * @return TournamentType[] Returns an array of TournamentType objects
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
    public function findOneBySomeField($value): ?TournamentType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
