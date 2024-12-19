<?php

namespace App\Stat\Repository;

use App\Entity\DrvUaStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;


/**
 * @method DrvUaStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvUaStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvUaStat[]    findAll()
 * @method DrvUaStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrvUaStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrvUaStat::class);
    }
}
