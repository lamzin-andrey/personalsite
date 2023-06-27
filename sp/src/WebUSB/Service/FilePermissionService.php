<?php


namespace App\WebUSB\Service;


use App\Entity\Ausers;
use App\Entity\DrvFile;
use App\Entity\DrvFilePermissions;
use App\Service\AppService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;
use StdClass;

class FilePermissionService
{
    private Registry $registry;
    private AppService $appService;

    /**
     * @var array
    */
    private $lastFileUsers;

    public function __construct(
        ManagerRegistry $registry,
        AppService $appService
    )
    {
        $this->registry = $registry;
        $this->appService = $appService;
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

    public function getShareModeAsJstring(int $fileId, bool $isPublic): string
    {
        $this->lastFileUsers = [];
        $repository = $this->registry->getRepository(DrvFilePermissions::class);
        $permissions = $repository->findBy([
            'fileId' => $fileId
        ]);

        $userIdList = $this->appService->extractUserId($permissions);
        if ($userIdList) {
            $repository = $this->registry->getRepository(Ausers::class);
            $this->lastFileUsers = $repository->findBy([
                'id' => $userIdList
            ]);
            if ($isPublic) {
                return 'bFPPublic';
            }
            return 'bFPCustom';
        }
        if ($isPublic) {
            return 'bFPPublic';
        }

        return 'bFPPrivate';
    }

    /**
     * @return array<int, StdClass {id, login}>
    */
    public function getUsersForLastFile(): array
    {
        $result = [];
        /**
         * @var Ausers $user
        */
        foreach ($this->lastFileUsers as $user) {
            $item = new StdClass();
            $item->login = $user->getUsername();
            $item->id = $user->getId();
            $result[] = $item;
        }

        return $result;
    }
}