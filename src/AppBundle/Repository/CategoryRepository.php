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
     * Get category
     *
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
     * Get category query builder result
     *
     * @param $categoryType
     * @return array
     */
    public function getCategoryByTypeResult($categoryType)
    {
        return $this->getCategoryByType($categoryType)->getQuery()->getResult();
    }

    /**
     * Get category query builder (form)
     *
     * @param $categoryType
     * @return QueryBuilder
     */
    public function getCategoryByType($categoryType)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.type = :category_type')
            ->setParameter('category_type', $categoryType);
        return $query;
    }

}
