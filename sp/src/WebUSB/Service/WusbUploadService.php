<?php
namespace App\WebUSB\Service;

use App\Entity\Ausers;
use App\Entity\DrvCatalogs;
use App\Entity\DrvFile;
use App\Service\AppService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use \Transliterator;

class WusbUploadService {
    private $registry;
    private Filesystem $filesystem;
    public function __construct(
        ContainerInterface $container,
        Filesystem $filesystem
    )
    {
        $this->registry = $container->get('doctrine');
        $this->filesystem = $filesystem;
    }
    
    public function getUploadCatalogId(
        $catalogId,
        $lang,
        $userId
    )
    {
        if($catalogId > 0) {
          return $catalogId;
        }
        $name = 'Загрузки';
        if ($lang === 'en') {
          $name = 'Uploads';
        }
        $repository = $this->registry->getRepository(DrvCatalogs::class) ;
        $c = $repository->findOneBy([
            'name' => $name,
            'parentId' => 0,
            'userId' => $userId
        ]);
        if (!$c) {
            $c = new DrvCatalogs();
            $c->setParentId(0);
            $c->setName($name);
            $c->setCreatedTime(new \DateTime());
            $c->setUpdatedTime(new \DateTime());
            $c->setUserId($userId);
            $this->registry->getManager()->persist($c);
            $this->registry->getManager()->flush();
        } else {
            /**
             * @var DrvCatalogs $c
            */
            if ($c->getIsDeleted()) {
                $c->setIsDeleted(false);
                $this->registry->getManager()->flush();
            }
        }

        return $c->getId();
    }

    /**
     * @param $
     * @return \StdClass {symlink:string, path: string, error: string, ext: string, userPath: string}
     */
    public function getFilePathObject(
        DrvFile $fileEntity,
        AppService $appService,
        ?Ausers $user
    ) : \StdClass
    {
        if (!$user) {
            $userId = $fileEntity->getUserId();
            $repository = $appService->repository(AUsers::class);
            $user = $repository->find($userId);
        }

        $result = new \StdClass();
        $result->symlink = '';
        $result->path = '';
        $result->userPath = '';
        $result->error = '';
        $result->ext = '';

        $filesystem = $this->filesystem;

        // start method
        $relativePath = $appService->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($user->getId());
        $catalogIdSubpath = '';
        $catalogEntity = $fileEntity->getCatalogEntity();
        if (!is_null($catalogEntity)) {
            $catalogIdSubpath = $catalogEntity->getId() . '/';
        }
        $result->ext = $ext = $this->getExtWithDot($fileEntity->getName());
        $root = __DIR__ . '/../../../..';
        $result->path = $root . $relativePath . '/' . $userPath . '/' . $catalogIdSubpath . $fileEntity->getId() .  $ext;
        $randHash = $appService->getHash2(date('Y-m-d H:i:s'), 'sha1');
        $result->userPath = $userPath . '/' . $catalogIdSubpath . $randHash . '/' .
            $appService->translite(
                preg_replace("#\s#mis", '_', $fileEntity->getName())
            );

        $relativePathForSymlink = str_replace('drive/d', 'drive/t', $relativePath);
        $symlink = $root . $relativePathForSymlink . '/' . $userPath . '/' . $catalogIdSubpath;
        $symlink = preg_replace("#/$#", '', $symlink);
        $filesystem->mkdir($symlink);

        if (!$filesystem->exists($symlink) || !is_dir($symlink)) {
            $result->error = 'Unable create temp catalog';

            return $result;
        }


        $transliterator = Transliterator::create('Any-Latin');
        $transliteratorToASCII = Transliterator::create('Latin-ASCII');
        $safeFilename = $transliteratorToASCII->transliterate($transliterator->transliterate($fileEntity->getName()));
        $safeFilename = preg_replace("#\s#", '_', $safeFilename);
        $symlink .= '/' . $fileEntity->getHash();
        $filesystem->mkdir($symlink);
        if (!$filesystem->exists($symlink) || !is_dir($symlink)) {
            $result->error = 'Unable create temp catalog (2)';

            return $result;
        }
        $symlink .= '/' . $safeFilename;
        $result->symlink = $symlink;

        return $result;
    }

    protected function generateUserPath(int $userId) : string
    {
        $n = floor($userId / 100) * 100 + 1;
        $s = ($n) . '-' . ($n + 100 - 1) . '/' . $userId;

        return $s;
    }

    protected function getExtWithDot(string $s) : string
    {
        $pathInfo = pathinfo($s);

        return strtolower(isset($pathInfo['extension']) ? ('.' . $pathInfo['extension']) : '');
    }
}
