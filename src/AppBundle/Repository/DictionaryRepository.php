<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class DictionaryRepository
 * @package AppBundle\Repository
 */
class DictionaryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * FormQueryBuilder to get all type of categories
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCategoryTypeList()
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', 'category_type');
    }

    /**
     * FormQueryBuilder to get all status of technical_evolution
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEvolutionStatusTypeList()
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', 'technical_evolution_status');
    }

    /**
     * FormQueryBuilder to get all origin of technical_evolution
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEvolutionOriginTypeList()
    {
        return $this->createQueryBuilder('d')
            ->where('d.type = :type')
            ->setParameter('type', 'technical_evolution_origin');
    }


    public function getStartingEvolutionStatus()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->andWhere('d.value = :value')
            ->setParameter('type', 'technical_evolution_status')
            ->setParameter('value', 'En cours')
        ;

        return $query->getQuery()->getResult();
    }
}
