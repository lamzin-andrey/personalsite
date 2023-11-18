<?php

namespace App\Repository;

use App\Entity\Ausers;
use App\Entity\DrvFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

/**
 * @method Ausers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ausers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ausers[]    findAll()
 * @method Ausers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ausers::class);
    }
    /**
     * @param $
     * @return
    */
    public function searchByLogin(string $s) : array
    {
        if (strlen($s) < 3) {

            return [];
        }
        $em = $this->getEntityManager();
        $sqlQuery = 'SELECT id, $username AS login FROM ausers 
                        WHERE $username = :s
                           OR $username LIKE(:s2)
                           OR $username LIKE(:s3)
                        ORDER BY ($username = :s) DESC,
                        CAST($username LIKE(:s2) AS INT) DESC,
                        CAST($username LIKE(:s3) AS INT) DESC
                        LIMIT 3;
                        ';
        /*$queryBuilder = $this->createQueryBuilder('u');
        $exp = $queryBuilder->expr();
        $queryBuilder->select('id, login');
        $queryBuilder->where($exp->eq('u.login', ':s'));
        $queryBuilder->orWhere($exp->like('u.login', ':s2'));
        $queryBuilder->orWhere($exp->like('u.login', ':s3'));*/
        $parameters = [
            's' => $s,
            's2' => "$s%",
            's3' => "%$s%"
        ];
        $conn = $em->getConnection();

        return $conn->executeQuery($sqlQuery, $parameters)->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
