<?php

namespace App\Stat\Repository;

use App\Entity\DrvUa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use \Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DrvUa|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrvUa|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrvUa[]    findAll()
 * @method DrvUa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrvUaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrvUa::class);
    }
}
