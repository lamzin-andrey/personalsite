<?php
namespace App\WebUSB\Service;

use App\Entity\DrvCatalogs;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WusbUploadService {
    private $registry;
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->registry = $container->get('doctrine');
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
}
