<?php


namespace App\Repository;


use App\Entity\DrvCatalogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @method DrvCatalogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvCatalogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvCatalogs[]    findAll()
 * @method DrvCatalogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class DrvCatalogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrvCatalogs::class);
    }
    /**
     * @param $
     * @return
    */
    public function addCatalogEntity(string $name, int $userId, int $parentId, bool &$alreadyExists) : int
    {
        $alreadyExists = false;
        $exists = $this->findOneBy([
            'userId' => $userId,
            'parentId' => $parentId,
            'name' => $name,
            'isDeleted' => false
        ]);

        if ($exists) {
            $alreadyExists = true;
            return intval($exists->getId());
        }

        if ($parentId > 0) {
            $parent = $this->find($parentId);
            if ($parent->getUserId() != $userId) {
                return 0;
            }
        }


        $dir = new DrvCatalogs();
        $dir->setUserId($userId);
        $dir->setParentId($parentId);
        $dir->setName($name);
        $dir->setCreatedTime(new \DateTime());
        $dir->setUpdatedTime(new \DateTime());
        $em = $this->getEntityManager();
        $em->persist($dir);
        $em->flush();

        return intval($dir->getId());
    }
    /**
     * @param $
     * @return
     */
    public function renameCatalogEntity(int $targetId, string $name, int $userId, int $parentId, bool &$alreadyExists) : int
    {
        $alreadyExists = false;
        $exists = $this->findOneBy([
            'userId' => $userId,
            'parentId' => $parentId,
            'name' => $name,
            'isDeleted' => false
        ]);

        if ($exists) {
            $alreadyExists = true;
            return intval($exists->getId());
        }

        if ($parentId > 0) {
            $parent = $this->find($parentId);
            if ($parent->getUserId() != $userId) {
                return 0;
            }
        }


        $dir = $parent = $this->find($targetId);
        $dir->setName($name);
        $em = $this->getEntityManager();
        $em->persist($dir);
        $em->flush();

        return intval($dir->getId());
    }

    /**
     * @param $
     * @return
    */
    public function getFlatIdListByUserId(int $userId) : array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $e = $queryBuilder->expr();
        $queryBuilder->where($e->eq('c.userId', ':userId'));
        $queryBuilder->setParameters([
            ':userId' => $userId
        ]);
        $queryBuilder->select('c.id', 'c.parentId', 'c.name');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $
     * @return
    */
    public function removeByIdList(array $idList, int $userId) : void
    {
        if (!count($idList)) {
            return;
        }
        $em = $this->getEntityManager();
        $sqlQuery = 'UPDATE `drv_catalogs` SET is_deleted = 1 WHERE id IN(:list) AND user_id = :userId';
        // $statement = $em->getConnection()->prepare($sqlQuery);
        // $statement->execute();
        $conn = $em->getConnection();
        $conn->executeUpdate($sqlQuery, [
                'list' => $idList,
                'userId' => $userId
            ],
            [
                'list' => Connection::PARAM_INT_ARRAY,
            ]
        );
    }

}