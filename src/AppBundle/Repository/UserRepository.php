<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

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

}