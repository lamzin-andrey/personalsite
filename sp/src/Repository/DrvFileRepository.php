<?php

namespace App\Repository;

use App\Entity\DrvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DrvFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvFile[]    findAll()
 * @method DrvFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrvFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrvFile::class);
    }
    /**
     * @param $
     * @return
    */
    public function removeById(int $fileId, int $userId) : void
    {
        if (!$fileId || !$userId) {

            return;
        }
        $em = $this->getEntityManager();
        $sqlQuery = 'UPDATE `drv_file` SET is_deleted = 1 WHERE id  = ' . $fileId . ' AND user_id = ' . $userId;
        $statement = $em->getConnection()->prepare($sqlQuery);
        $statement->execute();
        return;
    }

    // /**
    //  * @return DrvFile[] Returns an array of DrvFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DrvFile
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
