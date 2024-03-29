<?php

namespace App\Repository;

use App\Entity\SocnetUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SocnetUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocnetUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocnetUser[]    findAll()
 * @method SocnetUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocnetUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocnetUser::class);
    }

    // /**
    //  * @return SocnetUser[] Returns an array of SocnetUser objects
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
    public function findOneBySomeField($value): ?SocnetUser
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
