<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    const MAX_RESULT = 10;

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

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
    public function getList($page = 1, $maxUsers = 10, $company)
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

    /**
     * @param $page
     * @param $filter
     * @return Query
     */
    public function getRowsByPage($page, $filter)
    {
        $query = $this->createQueryBuilder('u')
            ->select('up','c', 'u')
            ->join('u.company','c', 'u.company = c.id')
            ->join('u.userProfile','up','u.userProfile = up.id')
            ->orderBy('c.id', 'DESC')
            ->setFirstResult(($page - 1) * self::MAX_RESULT)
            ->setMaxResults(self::MAX_RESULT);
        if(!is_null($filter)) {
            foreach ($filter as $field => $value) {
                if($value !== '') {
                    if(strpos($field, '_') !== 0) {
                        switch($field) {
                            case 'name':
                                $alias = 'c';
                                break;
                            case 'userProfile':
                                $alias = 'up';
                                break;
                            case 'firstname':
                                $alias = 'up';
                                break;
                            case 'lastname':
                                $alias = 'up';
                                break;
                            default:
                                $alias = 'u';
                        }
                        if ($alias == 'u' || $alias == 'c' || $alias == 'up') {
                            $search = "$alias.$field like '%$value%'";
                        } else {
                            $field = 'id';
                            $search ="$alias.$field = '$value'";
                        }
                        $query->andWhere($search);
                    }
                }
            }
        }
        return $query->getQuery();
    }

}



