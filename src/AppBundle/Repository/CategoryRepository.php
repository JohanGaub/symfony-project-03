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
     * @return array
     */
    public function getCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('c', 't')
            ->join('c.type', 't', 'c.type = t.id')
            ->orderBy('c.type')
            ->getQuery()
            ->getResult();
    }


    /**
     * @param $type
     * @return array
     */
    public function getCategoryByTypeResult($type)
    {
        return $this->getCategoryByType($type)->getQuery()->getResult();
    }

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
