<?php


namespace AppBundle\Repository;



use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository
{

    /**
     * @param int $page
     * @param int $maxUsers
     * @return \AppBundle\Repository\Paginator|Paginator
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
     * @param int $page
     * @param int $maxUsers
     * @param int $company
     * @return \AppBundle\Repository\Paginator|Paginator
     */
    public function getCoco($page = 1, $maxUsers = 10, $company)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.company = :company')
            ->setFirstResult(($page - 1) * $maxUsers)
            ->setMaxResults($maxUsers)
            ->setParameter('company', $company);

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

    public function getProjectResponsable( $roles, $company)
    {
            $qb = $this->createQueryBuilder('u')
                ->select('COUNT(u)')
                ->where('u.roles, :role')
                ->andWhere('u.company = :company')
                ->setParameter('role', $roles)
                ->setParameter('company', $company)
                ->getQuery()->getResult();

            return $qb;
        }


}


