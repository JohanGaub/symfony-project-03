<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * Class TechnicalEvolutionRepository
 * @package AppBundle\Repository
 */
class TechnicalEvolutionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param int $page
     * @param int $maxByPage
     * @param array $params
     * @return array
     */
    public function getListEvolution($page = 0, $maxByPage = 9, $params = [])
    {
        return $this->getListEvolutionNativeQuery($page, $maxByPage, $params)->getResult();
    }

    /**
     * @param $params
     * @return array
     */
    public function getNbEvolution($params)
    {
        return $this->getNbEvolutionNativeQuery($params)->getResult();
    }

    /**
     * getListEvolutionNativeQuery
     * get evolutions with multi params or not
     *
     * @param $page
     * @param $maxByPage
     * @param $params
     * @return \Doctrine\ORM\NativeQuery
     */
    private function getListEvolutionNativeQuery($page, $maxByPage, $params)
    {
        # create rsm object
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult($this->getEntityName(), 'te');
        $rsm->addFieldResult('te', 'id', 'id');
        $rsm->addFieldResult('te', 'title', 'title');
        $rsm->addFieldResult('te', 'sum_up', 'sumUp');
        $rsm->addFieldResult('te', 'status', 'status');
        $rsm->addFieldResult('te', 'origin', 'origin');
        $rsm->addFieldResult('te', 'expected_delay', 'expectedDelay');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Category', 'c', 'te', 'category');
        $rsm->addFieldResult('c', 'category_id', 'id');
        $rsm->addFieldResult('c', 'category_title', 'title');
        $rsm->addFieldResult('c', 'category_tag', 'tag');
        $rsm->addFieldResult('c', 'category_type', 'type');

        $rsm->addJoinedEntityResult('AppBundle\Entity\UserTechnicalEvolution', 'ute', 'te', 'userTechnicalEvolutions');
        $rsm->addFieldResult('ute', 'ute_id', 'id');
        $rsm->addFieldResult('ute', 'ute_type', 'type');
        $rsm->addFieldResult('ute', 'ute_note', 'note');
        $rsm->addFieldResult('ute', 'ute_comment', 'comment');
        $rsm->addFieldResult('ute', 'ute_technical_evolution_id', 'technical_evolution');

        # set entity name
        $table = $this->getClassMetadata()->getTableName();

        # set product start limit
        $startProduct = $page * $maxByPage;

        # get string params
        $searches = $this->getStringParameters($params);

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT te.id,
                te.title,
                te.sum_up,
                te.status,
                te.origin,
                te.expected_delay,
                c.id AS category_id,
                c.title AS category_title,
                c.tag AS category_tag,
                c.type AS category_type,
                ute.id AS ute_id,
                ute.type AS ute_type,
                ute.technical_evolution_id AS ute_technical_evolution,
                (SELECT COUNT(*)
                    FROM user_technical_evolution ute
                    WHERE ute.technical_evolution_id = te.id
                    AND ute.type = 'comment') AS ute_comment,
                (SELECT ROUND(AVG(ute.note) ,2)
                    FROM user_technical_evolution ute 
                    WHERE ute.technical_evolution_id = te.id
                    AND ute.type = 'note') AS ute_note
            FROM {$table} te 
            INNER JOIN category c ON te.category_id = c.id
            INNER JOIN user_technical_evolution ute ON ute.technical_evolution_id = te.id
            WHERE 1=1 AND {$searches}
            GROUP BY ute.id
            LIMIT {$startProduct}, {$maxByPage}
            
        ", $rsm);

        return $query;
    }

    /**
     * @param $params
     * @return \Doctrine\ORM\Query
     */
    private function getNbEvolutionNativeQuery($params)
    {
        # get string params
        $searches = $this->getStringParameters($params);

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createQuery("
            SELECT COUNT(te.id)
            FROM 'AppBundle\Entity\TechnicalEvolution' te 
            JOIN 'AppBundle\Entity\Category' c WITH te.category = c.id
            WHERE 1=1 AND {$searches}
            GROUP BY te.id
                
        ");

        return $query;
    }

    /**
     * @param array $params
     * @return array|mixed|string
     */
    private function getStringParameters(array $params)
    {
        /**
         * Get query parameters
         * Here we get all params send in function for search
         * Work with key LIKE 'value%'
         */
        $totalSearches = count($params);
        $searches = [];
        foreach ($params as $key => $value)
            $searches[] = $key . " LIKE " . "'%" . $value . "'";

        if (1 < $totalSearches)
            $searches = implode(' AND ', $searches);
        elseif (1 == $totalSearches)
            $searches = $searches[0];
        else
            $searches = '0=0';

        return $searches;
    }
}
