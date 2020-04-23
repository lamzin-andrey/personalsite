<?php

namespace App\Repository;

use App\Entity\CrnTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CrnTasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrnTasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrnTasks[]    findAll()
 * @method CrnTasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrnTasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrnTasks::class);
    }

    // /**
    //  * @return KxmQuest[] Returns an array of KxmQuest objects
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
    public function findOneBySomeField($value): ?KxmQuest
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
