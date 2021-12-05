<?php

namespace App\Repository;

use App\Entity\UserTempPasswords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserTempPasswords|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTempPasswords|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTempPasswords[]    findAll()
 * @method UserTempPasswords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTempPasswordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTempPasswords::class);
    }

    // /**
    //  * @return UserTempPasswords[] Returns an array of UserTempPasswords objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTempPasswords
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
