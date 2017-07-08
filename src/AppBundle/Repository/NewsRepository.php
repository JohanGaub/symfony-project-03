<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NewsRepository
 * @package AppBundle\Repository
 */
class NewsRepository extends EntityRepository
{
    /**
     * @param $type
     * @return array
     */
    public function getNewsByType($type)
    {
        return $this->createQueryBuilder('n')
            ->select('n', 't')
            ->join('n.type', 't', 'n.status = t.id')
            ->where('t.value = :type')
            ->andWhere('n.isVisible = true')
            ->setParameter('type', $type)
            ->setMaxResults(3)
            ->orderBy('n.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}