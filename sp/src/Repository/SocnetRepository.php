<?php

namespace App\Repository;

use App\Entity\Socnet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Socnet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Socnet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Socnet[]    findAll()
 * @method Socnet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocnetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Socnet::class);
    }

    // /**
    //  * @return Socnet[] Returns an array of Socnet objects
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
    public function findOneBySomeField($value): ?Socnet
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
