<?php

namespace App\WebUSB\Repository;

use App\Entity\Ausers;
use App\Entity\DrvFilePermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @method DrvFilePermissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvFilePermissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvFilePermissions[]    findAll()
 * @method DrvFilePermissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrvFilePermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry  $registry)
    {
        parent::__construct($registry, DrvFilePermissions::class);
    }

    public function shareFileForUser(Ausers $user, $fileId)
    {
        $this->shareFileForUserId($user->getId(), $fileId);
    }

    public function shareFilesForUser(Ausers $user, array $fileIdList)
    {
        $this->shareFilesForUserId($user->getId(), $fileIdList);
    }

    public function shareFileForUserId(int $userId, int $fileId, bool $saveImmediately = true)
    {
        $e = new DrvFilePermissions();
        $e->setUserId($userId);
        $e->setFileId($userId);
        $e->setCreatedTime(new DateTime());
        $this->getEntityManager()->persist($e);
        if ($saveImmediately) {
            $this->getEntityManager()->flush();
        }
    }

    public function shareFilesForUserId(int $userId, array $fileIdList)
    {
        foreach ($fileIdList as $fileId) {
            $this->shareFileForUserId($userId, $fileId, false);
        }
        $this->getEntityManager()->flush();
    }

}
