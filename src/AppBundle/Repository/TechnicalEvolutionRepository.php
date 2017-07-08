<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class TechnicalEvolutionRepository
 * @package AppBundle\Repository
 */
class TechnicalEvolutionRepository extends EntityRepository
{

    /**
     * @param string $params
     * @param int $page
     * @param int $maxByPage
     * @return array
     */
    public function getListEvolution(string $params = '', int $page = 0, int $maxByPage = 9)
    {
        return $this->getListEvolutionNativeQuery($params, $page, $maxByPage)->getResult();
    }

    /**
     * @param int $technicalEvolutionId
     * @return array
     */
    public function getUnitEvolution(int $technicalEvolutionId)
    {
        return $this->getUnitEvolutionNativeQuery($technicalEvolutionId)->getScalarResult();
    }

    /**
     * @return array
     */
    public function getListWaitingEvolution()
    {
        return $this->getListWaitingEvolutionNativeQuery()->getResult();
    }

    /**
     * @param string $params
     * @return array
     */
    public function getNbEvolution(string $params = '')
    {
        return $this->getNbEvolutionQuery($params)->getResult();
    }

    /**
     * getListEvolutionNativeQuery
     * get evolutions with multi params or not
     *
     * @param string $params
     * @param int $page
     * @param int $maxByPage
     * @return \Doctrine\ORM\NativeQuery
     */
    private function getListEvolutionNativeQuery($params, int $page,int $maxByPage)
    {
        # create rsm object
        $rsm        = new ResultSetMapping();
        $rsmGetter  = new EntityFieldResult($rsm);
        $rsm->addEntityResult($this->getEntityName(), 'te');
        $rsm = $rsmGetter->rsmBasicTechnicalEvolution('te');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dtes', 'te', 'status');
        $rsm = $rsmGetter->rsmDictionaryStatus('dtes');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dteo', 'te', 'origin');
        $rsm = $rsmGetter->rsmDictionaryOrigin('dteo');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Category', 'c', 'te', 'category');
        $rsm = $rsmGetter->rsmCategory('c');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'ct', 'c', 'type');
        $rsm = $rsmGetter->rsmCategoryType('ct');
        $rsm->addJoinedEntityResult('AppBundle\Entity\UserTechnicalEvolution', 'ute', 'te', 'userTechnicalEvolutions');
        $rsm = $rsmGetter->rsmUserTechnicalEvolution('ute');

        # set entity name
        $table = $this->getClassMetadata()->getTableName();

        # set product start limit
        $startProduct = $page * $maxByPage;

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT te.id,
                te.title,
                te.sum_up,
                te.status,
                te.origin,
                te.expected_delay,
                te.creation_date,
                te.update_date,
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
            WHERE 1=1 AND {$params}
            GROUP BY ute.id
            LIMIT {$startProduct}, {$maxByPage}
            
        ", $rsm);

        return $query;
    }

    /**
     * getUnitEvolutionNativeQuery
     * get evolutions unit by id
     *
     * @param int $technicalEvolutionId
     * @return \Doctrine\ORM\NativeQuery
     */
    private function getUnitEvolutionNativeQuery(int $technicalEvolutionId)
    {
        # create rsm object
        $rsm        = new ResultSetMapping();
        $rsmGetter  = new EntityFieldResult($rsm);
        $rsm->addEntityResult($this->getEntityName(), 'te');
        $rsm = $rsmGetter->rsmBasicTechnicalEvolution('te');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dtes', 'te', 'status');
        $rsm = $rsmGetter->rsmDictionaryStatus('dtes');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dteo', 'te', 'origin');
        $rsm = $rsmGetter->rsmDictionaryOrigin('dteo');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Category', 'c', 'te', 'category');
        $rsm = $rsmGetter->rsmCategory('c');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'ct', 'c', 'type');
        $rsm = $rsmGetter->rsmCategoryType('ct');

        # set entity name
        $table = $this->getClassMetadata()->getTableName();

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT te.id,
                te.title,
                te.sum_up,
                te.status,
                te.origin,
                te.expected_delay,
                te.creation_date,
                te.update_date,
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
                ct.value AS dictionary_ct_value
            FROM {$table} te 
            INNER JOIN category c ON te.category_id = c.id
            INNER JOIN dictionary ct ON c.type = ct.id
            INNER JOIN dictionary dtes ON te.status = dtes.id
            INNER JOIN dictionary dteo ON te.origin = dteo.id
            INNER JOIN user_technical_evolution ute ON ute.technical_evolution_id = te.id
            WHERE te.id = {$technicalEvolutionId}
            LIMIT 1
            
        ", $rsm);

        return $query;
    }

    /**
     * @return \Doctrine\ORM\NativeQuery
     */
    private function getListWaitingEvolutionNativeQuery()
    {
        # create rsm object
        $rsm        = new ResultSetMapping();
        $rsmGetter  = new EntityFieldResult($rsm);
        $rsm->addEntityResult($this->getEntityName(), 'te');
        $rsm = $rsmGetter->rsmBasicTechnicalEvolution('te');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dtes', 'te', 'status');
        $rsm = $rsmGetter->rsmDictionaryStatus('dtes');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'dteo', 'te', 'origin');
        $rsm = $rsmGetter->rsmDictionaryOrigin('dteo');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Category', 'c', 'te', 'category');
        $rsm = $rsmGetter->rsmCategory('c');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Dictionary', 'ct', 'c', 'type');
        $rsm = $rsmGetter->rsmCategoryType('ct');

        # set entity name
        $table = $this->getClassMetadata()->getTableName();

        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT te.id,
                te.title,
                te.sum_up,
                te.status,
                te.origin,
                te.expected_delay,
                te.creation_date,
                te.update_date,
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
                ct.value AS dictionary_ct_value
            FROM {$table} te 
            INNER JOIN category c ON te.category_id = c.id
            INNER JOIN dictionary ct ON c.type = ct.id
            INNER JOIN dictionary dtes ON te.status = dtes.id
            INNER JOIN dictionary dteo ON te.origin = dteo.id
            INNER JOIN user_technical_evolution ute ON ute.technical_evolution_id = te.id
            WHERE dtes.value = 'En attente'
            
        ", $rsm);

        return $query;
    }

    /**
     * @param string $params
     * @return \Doctrine\ORM\Query
     */
    private function getNbEvolutionQuery(string $params)
    {
        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createQuery("
            SELECT te.id
            FROM 'AppBundle\Entity\TechnicalEvolution' te 
            JOIN 'AppBundle\Entity\Dictionary' dtes WITH te.status = dtes.id
            JOIN 'AppBundle\Entity\Dictionary' dteo WITH te.origin = dteo.id
            JOIN 'AppBundle\Entity\Category' c WITH te.category = c.id
            JOIN 'AppBundle\Entity\Dictionary' ct WITH c.type = ct.id
            WHERE 1=1 AND {$params}
            GROUP BY te.id
                
        ");

        return $query;
    }

    /**
     * @param $technicalEvolutionId
     * @return array
     */
    public function getScoreForTechnicalEvolution($technicalEvolutionId)
    {
        $qb = $this->createQueryBuilder('te')
            ->join("te.userTechnicalEvolutions", "ute")
            ->where("te.id = $technicalEvolutionId")
            ->andWhere("ute.note IS NOT NULL")
            ->select('SUM(ute.note)', 'count(ute.note)', 'AVG(ute.note)')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * @param $technicalEvolutionId
     * @param $userId
     * @return array
     */
    public function getNoteByUserPerTechnicalEvolution($technicalEvolutionId, $userId)
    {
        $qb = $this->createQueryBuilder('te')
            ->select("ute.note")
            ->join("te.userTechnicalEvolutions", "ute")
            ->join("ute.user", "u")
            ->where("te.id = $technicalEvolutionId")
            ->andWhere("u.id = $userId")
            ->andWhere("ute.note IS NOT NULL")
            ->getQuery();
        return $qb->getResult();

    }

}
