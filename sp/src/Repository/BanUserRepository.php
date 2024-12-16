<?php

namespace App\Repository;

use App\Entity\Ausers;
use App\Entity\BanUsers;
use App\Entity\DrvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

/**
 * @method BanUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method BanUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method BanUsers[]    findAll()
 * @method BanUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BanUsers::class);
    }
}
