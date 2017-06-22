<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package AppBundle\Repository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCategoryNameList($type)
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $type);
    }
}
