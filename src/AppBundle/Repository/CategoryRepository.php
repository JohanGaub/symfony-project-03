<?php

namespace AppBundle\Repository;

/**
 * Class CategoryRepository
 * @package AppBundle\Repository
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $typeId
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCategoryNameList($typeId)
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $typeId);
    }
}
