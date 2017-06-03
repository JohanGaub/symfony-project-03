<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;

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
    public function getNbEvolution($params = [])
    {
        return $this->getNbEvolutionQuery($params)->getResult();
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
        $rsm->addFieldResult('te', 'expected_delay', 'expectedDelay');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dtes', 'te', 'status');
        $rsm->addFieldResult('dtes', 'dictionary_tes_id', 'id');
        $rsm->addFieldResult('dtes', 'dictionary_tes_type', 'type');
        $rsm->addFieldResult('dtes', 'dictionary_tes_value', 'value');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dteo', 'te', 'origin');
        $rsm->addFieldResult('dteo', 'dictionary_teo_id', 'id');
        $rsm->addFieldResult('dteo', 'dictionary_teo_type', 'type');
        $rsm->addFieldResult('dteo', 'dictionary_teo_value', 'value');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Category', 'c', 'te', 'category');
        $rsm->addFieldResult('c', 'category_id', 'id');
        $rsm->addFieldResult('c', 'category_title', 'title');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'ct', 'c', 'type');
        $rsm->addFieldResult('ct', 'dictionary_ct_id', 'id');
        $rsm->addFieldResult('ct', 'dictionary_ct_type', 'type');
        $rsm->addFieldResult('ct', 'dictionary_ct_value', 'value');

        $rsm->addJoinedEntityResult('AppBundle\Entity\UserTechnicalEvolution', 'ute', 'te', 'userTechnicalEvolutions');
        $rsm->addFieldResult('ute', 'ute_id', 'id');
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
                dtes.id AS dictionary_tes_id,
                dtes.type AS dictionary_tes_type,
                dtes.value AS dictionary_tes_value,
                dteo.id AS dictionary_teo_id,
                dteo.type AS dictionary_teo_type,
                dteo.value AS dictionary_teo_value,
                c.id AS category_id,
                c.title AS category_title,
                c.type AS category_type,
                ct.id AS dictionary_ct_id,
                ct.type AS dictionary_ct_type,
                ct.value AS dictionary_ct_value,
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
            INNER JOIN dictionary ct ON c.type = ct.id
            INNER JOIN dictionary dtes ON te.status = dtes.id
            INNER JOIN dictionary dteo ON te.origin = dteo.id
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
    private function getNbEvolutionQuery($params)
    {
        # get string params
        $searches = $this->getStringParameters($params);

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createQuery("
            SELECT te.id
            FROM 'AppBundle\Entity\TechnicalEvolution' te 
            JOIN 'AppBundle\Entity\Dictionary' dtes WITH te.status = dtes.id
            JOIN 'AppBundle\Entity\Dictionary' dteo WITH te.origin = dteo.id
            JOIN 'AppBundle\Entity\Category' c WITH te.category = c.id
            JOIN 'AppBundle\Entity\Dictionary' ct WITH c.type = ct.id
            WHERE 1=1 AND {$searches}
            GROUP BY te.id
                
        ");

        return $query;
    }

    /**
     * @param array $params
     * @return string
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
            $searches[] = $key . " LIKE " . "'" . $value . "%'";

        if (1 < $totalSearches)
            $searches = implode(' AND ', $searches);
        elseif (1 == $totalSearches)
            $searches = $searches[0];
        else
            $searches = '0=0';

        return (string) $searches;
    }
}
