<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 21/06/17
 * Time: 16:26
 */

namespace AppBundle\Repository;



use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository
{

    /**
     * @param int $page
     * @param int $maxUsers
     * @return Paginator
     */
    public function getListing($page = 1, $maxUsers = 10)
    {
        $qb = $this->createQueryBuilder('u')
            ->join('u.company', 'c')
            ->orderBy('c.name', 'ASC')
            ->setFirstResult(($page - 1) * $maxUsers)
            ->setMaxResults($maxUsers);

        return new Paginator($qb, $fetchJoinCollection = false);
    }

    /**
     * @return mixed
     */
    public function countUserTotal()
    {
        $q = $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()->getSingleScalarResult();

        return $q;
    }

    public function getProjectResponsable()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.company', 'id')
            ->select('COUNT(u.roles)', 'ROLE_PROJECT_RESP');

            return $qb;
    }
}