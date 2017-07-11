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
     * Get evolution with a bit informations
     *
     * @param $params
     * @return array
     */
    public function getSimpleEvolutions($params)
    {
        $qb = $this->createQueryBuilder('te')
            ->select('te.id', 'te.title', 'te.sumUp', 'te.creationDate', 'te.updateDate', 'te.reason', 'te.expectedDelay')
            ->addSelect('dtes.value as status')
            ->addSelect('dteo.value as origin')
            ->addSelect('c.title as category_title')
            ->addSelect('ct.value as category_type')
            ->addSelect('u.id as user_id')
            ->join('te.status', 'dtes', 'te.status = dtes.id')
            ->join('te.origin', 'dteo', 'te.origin = dteo.id')
            ->join('te.category', 'c', 'te.category = c.id')
            ->join('te.user', 'u', 'te.user = u.id')
            ->join('c.type', 'ct', 'c.type = ct.id')
            ->orderBy('te.id');
        if (!is_null($params)) {
            foreach ($params as $key => $val) {
                $qb->andWhere("$key = '$val'");
            }
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * Get evolutions
     *
     * @param $params
     * @param $page
     * @param $maxByPage
     * @return array
     */
    public function getEvolutions($params, $page, $maxByPage)
    {
        $qb = $this->createQueryBuilder('te')
            ->select('te.id', 'te.title', 'te.sumUp', 'te.creationDate', 'te.updateDate', 'te.reason', 'te.expectedDelay')
            ->addSelect('dtes.value as status')
            ->addSelect('dteo.value as origin')
            ->addSelect('c.title as category_title')
            ->addSelect('ct.value as category_type')
            ->addSelect('u.id as user_id')
            ->addSelect('COUNT(ute.note) as nb_notes')
            ->addSelect('COUNT(ute.comment) as nb_comments')
            ->addSelect('AVG(ute.note) as avg_notes')
            ->join('te.status', 'dtes', 'te.status = dtes.id')
            ->join('te.origin', 'dteo', 'te.origin = dteo.id')
            ->join('te.category', 'c', 'te.category = c.id')
            ->join('te.user', 'u', 'te.user = u.id')
            ->join('c.type', 'ct', 'c.type = ct.id')
            ->join('te.userTechnicalEvolutions', 'ute', 'te.id = ute.technicalEvolution')
            ->where('1 = 1 AND ' . $params)
            ->orderBy('te.id')
            ->groupBy('te.id')
            ->setFirstResult(($page - 1) * $maxByPage)
            ->setMaxResults($maxByPage);
        return $qb->getQuery()->getResult();
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
     * @param string $params
     * @return array
     */
    public function getNbEvolution(string $params = '')
    {
        return $this->getNbEvolutionQuery($params)->getSingleScalarResult();
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
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult($this->getEntityName(), 'te');
        $rsm->addFieldResult('te', 'id', 'id');
        $rsm->addFieldResult('te', 'title', 'title');
        $rsm->addFieldResult('te', 'sum_up', 'sumUp');
        $rsm->addFieldResult('te', 'expected_delay', 'expectedDelay');
        $rsm->addFieldResult('te', 'creation_date', 'creationDate');
        $rsm->addFieldResult('te', 'update_date', 'updateDate');
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
        # set entity name
        $table = $this->getClassMetadata()->getTableName();

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
     * TODO => Don't forget to delete (filter-search)
     * @param string $params
     * @return \Doctrine\ORM\Query
     */
    private function getNbEvolutionQuery(string $params)
    {
        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createQuery("
            SELECT COUNT(te)
            FROM 'AppBundle\Entity\TechnicalEvolution' te 
            JOIN 'AppBundle\Entity\Dictionary' dtes WITH te.status = dtes.id
            JOIN 'AppBundle\Entity\Dictionary' dteo WITH te.origin = dteo.id
            JOIN 'AppBundle\Entity\Category' c WITH te.category = c.id
            JOIN 'AppBundle\Entity\Dictionary' ct WITH c.type = ct.id
            WHERE 1=1 AND {$params} 
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
