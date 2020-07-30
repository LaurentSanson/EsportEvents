<?php

namespace App\Repository;

use App\Entity\TournamentStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TournamentStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentStyle[]    findAll()
 * @method TournamentStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentStyle::class);
    }

    // /**
    //  * @return TournamentStyle[] Returns an array of TournamentStyle objects
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
    public function findOneBySomeField($value): ?TournamentStyle
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
