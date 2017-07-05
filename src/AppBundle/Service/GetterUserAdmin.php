<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class GetterUserAdmin
 * @package AppBundle\Service
 */
class GetterUserAdmin
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GetterUserAdmin constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return all users have role admin && super_admin
     * @return array
     */
    public function getAllAdmin()
    {
        return $this->getQuery('%admin%');
    }

    /**
     * Return all users have role admin
     * @return array
     */
    public function getAdmin()
    {
        return $this->getQuery('%role_admin%');
    }

    /**
     * Return all users have role super_admin
     * @return array
     */
    public function getSuperAdmin()
    {
        return $this->getQuery('%role_super_admin%');
    }

    /**
     * Query, return users inject "%roles%" needed
     * @param string $search
     * @return array
     */
    private function getQuery(string $search)
    {
        /** @noinspection SqlResolve */
        return $this->em->createQuery("
            SELECT u 
            FROM 'AppBundle\Entity\User' u
            WHERE u.roles LIKE :roles AND WHERE u.company = :company
        ")->setParameter('roles', $search)->getResult();
    }
}