<?php

namespace App\Repository;

use App\Entity\DrvBookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method DrvBookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvBookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvBookmark[]    findAll()
 * @method DrvBookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrvBookmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrvBookmark::class);
    }

    public function save(string $s, int $userId)
    {
        $entity = $this->findOneBy(['userId' => $userId]);
        if (!$entity) {
            $entity = new DrvBookmark();
            $entity->setUserId($userId);
            $this->getEntityManager()->persist($entity);
        }
        $entity->setBm($s);
        $this->getEntityManager()->flush();
    }
}
