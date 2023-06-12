<?php


namespace App\WebUSB\Service;


use App\Entity\DrvFile;
use App\Entity\DrvFilePermissions;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;

class FilePermissionService
{
    private Registry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function hasAccessToFile(int $userId, DrvFile $file): bool
    {
        if ($file->getUserId() == $userId) {
            return true;
        }

        $repository = $this->registry->getRepository(DrvFilePermissions::class);
        $permission = $repository->findOneBy([
            'userId' => $userId,
            'fileId' => $file->getId()
        ]);

        if ($permission) {
            return true;
        }

        return false;
    }
}