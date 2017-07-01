<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class CategoryRepository
 * @package AppBundle\Repository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param $categoryType
     * @return QueryBuilder
     */
    public function getCategoryByType($categoryType)
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :category_type')
            ->setParameter('category_type', $categoryType);

    }
}
