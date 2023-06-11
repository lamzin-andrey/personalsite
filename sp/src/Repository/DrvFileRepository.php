<?php

namespace App\Repository;

use App\Entity\Ausers;
use App\Entity\DrvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

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

    /**
     * @param $
     * @return
     */
    public function renameFileEntity(int $targetId, string $name, int $userId, int $catalogId, bool &$alreadyExists) : int
    {
        $alreadyExists = false;
        $exists = $this->findOneBy([
            'userId' => $userId,
            'catalogId' => $catalogId,
            'name' => $name,
            'isDeleted' => false
        ]);

        if ($exists) {
            $alreadyExists = true;
            return intval($exists->getId());
        }


        $file = $this->find($targetId);
        if (!$file) {
            return 0;
        }
        if ($file->getUserId() != $userId) {
            return 0;
        }
        $file->setName($name);
        $em = $this->getEntityManager();
        $em->persist($file);
        $em->flush();

        return intval($file->getId());
    }

    /**
     * @param $
     * @return
    */
    public function getCurrentSize(Ausers $user) : int
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $e = $queryBuilder->expr();
        $queryBuilder->select('SUM(f.size) AS s');
        $queryBuilder->where($e->eq('f.userId', ':userId'));
        $queryBuilder->andWhere($e->eq('f.isDeleted', ':zero'));
        $queryBuilder->setParameters([
            ':userId' => $user->getId(),
            ':zero' => 0
        ]);
        $rows = $queryBuilder->getQuery()->execute();
        if (count($rows) > 0) {
            return intval($rows[0]['s']);
        }
        return 0;
    }

    public function removeByCatalogIdList(array $catalogIdList, $userId)
    {
        if (!count($catalogIdList)) {
            return;
        }
        $em = $this->getEntityManager();
        $sqlQuery = 'UPDATE `drv_file` SET is_deleted = 1 WHERE catalog_id IN(:list) AND user_id = :userId';
        $conn = $em->getConnection();
        $conn->executeUpdate($sqlQuery, [
                'list' => $catalogIdList,
                'userId' => $userId
            ],
            [
                'list' => Connection::PARAM_INT_ARRAY,
            ]
        );
    }
}
