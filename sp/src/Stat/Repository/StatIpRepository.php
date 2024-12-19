<?php

namespace App\Stat\Repository;

use App\Entity\StatIp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method StatIp|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatIp|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatIp[]    findAll()
 * @method StatIp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatIpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatIp::class);
    }
}
