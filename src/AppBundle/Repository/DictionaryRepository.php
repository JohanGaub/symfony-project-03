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
     * FormQueryBuilder to get all type of dictionary
     *
     * @param string $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getItemListByType(string $type)
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', $type);
    }

}
