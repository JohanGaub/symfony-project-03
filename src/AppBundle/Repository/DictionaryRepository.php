<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DictionaryRepository
 * @package AppBundle\Repository
 */
class DictionaryRepository extends EntityRepository
{
    /**
     * @param $type
     * @return array
     */
    public function getItemListByTypeResult($type)
    {
        return $this->getItemListByType($type)->getQuery()->getResult();
    }

    /**
     * FormQueryBuilder to get all type of dictionary
     *
     * @param $type
     * @return QueryBuilder
     */
    public function getItemListByType($type)
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', $type);
    }

}
