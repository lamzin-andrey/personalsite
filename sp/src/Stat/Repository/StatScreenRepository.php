<?php

namespace App\Stat\Repository;

use App\Entity\StatScreen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method StatScreen|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatScreen|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatScreen[]    findAll()
 * @method StatScreen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatScreenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatScreen::class);
    }
}
