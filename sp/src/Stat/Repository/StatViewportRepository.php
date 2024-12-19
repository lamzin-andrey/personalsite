<?php

namespace App\Stat\Repository;

use App\Entity\StatViewport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method StatViewport|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatViewport|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatViewport[]    findAll()
 * @method StatViewport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatViewportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatViewport::class);
    }
}
