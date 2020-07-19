<?php

namespace App\Repository;

use App\Entity\KxmQuest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KxmQuest|null find($id, $lockMode = null, $lockVersion = null)
 * @method KxmQuest|null findOneBy(array $criteria, array $orderBy = null)
 * @method KxmQuest[]    findAll()
 * @method KxmQuest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KxmQuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KxmQuest::class);
    }

    public function getManager()
	{
		return $this->getEntityManager();
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
