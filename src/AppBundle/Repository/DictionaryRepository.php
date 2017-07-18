<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getItemListByType($type)
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', $type);
    }

    /**
     * FormQueryBuilder to get all type of dictionary (name)
     *
     * @param $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getItemNameByType($type)
    {
        return $this->createQueryBuilder('d')
            ->select('d.title')
            ->where('d.type = :type')
            ->setParameter('type', $type);
    }

}
