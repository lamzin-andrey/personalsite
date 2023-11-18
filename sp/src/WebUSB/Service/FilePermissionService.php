<?php


namespace App\WebUSB\Service;


use App\Entity\Ausers;
use App\Entity\DrvFile;
use App\Entity\DrvFilePermissions;
use App\Repository\DrvFileRepository;
use App\Service\AppService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use StdClass;
use Symfony\Contracts\Translation\TranslatorInterface;

class FilePermissionService
{
    private Registry $registry;
    private AppService $appService;
    private TranslatorInterface $t;

    /**
     * @var array
    */
    private $lastFileUsers;

    public function __construct(
        ManagerRegistry $registry,
        AppService $appService,
        TranslatorInterface $t
    )
    {
        $this->registry = $registry;
        $this->appService = $appService;
        $this->t = $t;
    }

    public function hasAccessToFile($userId, DrvFile $file): bool
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

    public function getShareModeAsJstring($fileId, $isPublic): string
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

    public function saveFilePermission($fileId, $isPublic): void
    {
        /**
         * @var DrvFileRepository $fileRepository
         */
        $fileRepository = $this->registry->getRepository(DrvFile::class);
        $fileEntity = $fileRepository->find($fileId);
        if ($fileEntity) {
            $fileEntity->setIsPublic($isPublic);
            $this->appService->save($fileEntity);
        }
    }

    public function isOwner($userId,  $fileId, ?array &$response = null): bool
    {
        /**
         * @var DrvFileRepository $fileRepository
         */
        $fileRepository = $this->registry->getRepository(DrvFile::class);
        // remove phisical + symlink
        $fileEntity = $fileRepository->find($fileId);
        if ($fileEntity->getUserId() != $userId) {
            $response = [
                'status' => 'error',
                'error' => 'You have not access to this page'
            ];

            return false;
        }

        return true;
    }

    public function addFileUser($fileId, $userId): void
    {
        $sql = "INSERT INTO `drv_file_permissions`
                (`user_id`, `file_id`, `created_time`)
                VALUES(:u, :f, :t)
                ON DUPLICATE KEY UPDATE `user_id` = `user_id`
        ";
        $parameters = [
            'u' => $userId,
            'f' => $fileId,
            't' => date('Y-m-d H:i:s')
        ];
        /**
         * @var Connection $conn
        */
        $conn = $this->registry->getConnection();
        $conn->executeUpdate($sql, $parameters);
    }
}

