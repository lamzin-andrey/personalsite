<?php


namespace App\Repository;


use App\Entity\DrvCatalogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
        $em = $this->getEntityManager();
        $em->persist($dir);
        $em->flush();

        return intval($dir->getId());
    }

}