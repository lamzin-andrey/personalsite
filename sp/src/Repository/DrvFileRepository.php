<?php

namespace App\Repository;

use App\Entity\Ausers;
use App\Entity\DrvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

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

    public function setIsDeletedById(int $fileId, int $userId) : void
    {
        if (!$fileId || !$userId) {
            return;
        }
        $em = $this->getEntityManager();
        $sqlQuery = 'UPDATE `drv_file` 
                        SET is_deleted = 1,
                            is_no_erased = 0
                        WHERE id  = ' . $fileId . ' AND user_id = ' . $userId;
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
        $queryBuilder->andWhere($e->eq('f.isNoErased', ':one'));
        $queryBuilder->setParameters([
            ':userId' => $user->getId(),
            ':one' => 1
        ]);
        $rows = $queryBuilder->getQuery()->execute();
        if (count($rows) > 0) {
            return intval($rows[0]['s']);
        }
        return 0;
    }

    /**
     * @param int $legalIntervalSeconds через какое кол-во времени мы можем по закону удалить файл
    */
    public function removeByCatalogIdList(array $catalogIdList, int $userId, int $legalIntervalSeconds)
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

        // И то же самое с учетом нового поля и закона Яровой.
        $sqlQuery = 'UPDATE `drv_file` SET is_no_erased = 0
                        WHERE 
                              catalog_id IN(:list) 
                              AND user_id = :userId
                              AND created_time < :legalTime';
        $legalTime = time() - $legalIntervalSeconds;
        $legalDateTimeStr = date('Y-m-d H:i:s', $legalTime);
        $conn = $em->getConnection();
        $conn->executeUpdate($sqlQuery, [
            'list' => $catalogIdList,
            'userId' => $userId,
            'legalTime' => $legalDateTimeStr
        ],
            [
                'list' => Connection::PARAM_INT_ARRAY,
            ]
        );
    }

    public function getFreePerDay(int $userId, int $legalIntervalSeconds):int
    {
        return $this->getFreePerInterval($userId, $legalIntervalSeconds, 24*3600);
    }

    public function getFreePerWeek(int $userId, int $legalIntervalSeconds):int
    {
        return $this->getFreePerInterval($userId, $legalIntervalSeconds, 7*24*3600);
    }
    public function getFreePerMonth(int $userId, int $legalIntervalSeconds):int
    {
        return $this->getFreePerInterval($userId, $legalIntervalSeconds, 31*24*3600);
    }
    public function getFreePer6Months(int $userId, int $legalIntervalSeconds):int
    {
        return $this->getFreePerInterval($userId, $legalIntervalSeconds, 6*31*24*3600);
    }

    private function getFreePerInterval(int $userId, int $legalIntervalSeconds, int $interval):int
    {
        $freeTime = time() + $interval - $legalIntervalSeconds ;
        $freeDate = date('Y-m-d H:i:s', $freeTime);
        $sql = 'SELECT SUM(`size`) AS c 
                FROM drv_file 
                WHERE 
                      user_id = :userId
                      AND is_deleted = 1
                      AND is_no_erased = 1
                      AND created_time < :freeTime
                ';
        $p = [
            'freeTime' => $freeDate,
            'userId' => $userId
        ];


        $r = $this->getEntityManager()->getConnection()->executeQuery($sql, $p)->fetchAll(FetchMode::ASSOCIATIVE);

        return (int)$r[0]['c'];
    }
}
