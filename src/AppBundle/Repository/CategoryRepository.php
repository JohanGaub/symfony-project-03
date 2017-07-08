<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Dictionary;
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
    public function getCategorys()
    {
        return $this->createQueryBuilder('c')
            ->select('c', 't')
            ->join('c.type', 't', 'c.type = t.id')
            ->orderBy('c.type')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $categoryType
     * @return array
     */
    public function getCategoryByTypeResult($categoryType)
    {
        return $this->getCategoryByType($categoryType)->getQuery()->getResult();
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
