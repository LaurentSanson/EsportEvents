<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @return Player[] Returns an array of Player objects
     */
    public function searchPlayer($search)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.nickname LIKE :search')
            ->setParameter('search', "%" . $search . "%")
            ->orderBy('p.nickname', 'ASC')
            ->setMaxResults(5);

        $query = $qb->getQuery();

        return $query->getResult();


    }



    public function findOneByResetToken($token): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.resetToken = :resetToken')
            ->setParameter('resetToken', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
