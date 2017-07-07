<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class UserTechnicalEvolutionRepository
 * @package AppBundle\Repository
 */
class UserTechnicalEvolutionRepository extends EntityRepository
{
    /**
     * @param int $evolution
     * @param string $type
     * @param mixed $limit
     * @return mixed
     */
    public function getUserTechnicalEvolution(int $evolution, string $type, $limit)
    {
        return $this->getUserTechnicalEvolutionNativeQuery($evolution, $type, $limit)->getResult();
    }

    /**
     * @param int $evolution
     * @param string $type
     * @param mixed $limit
     * @return mixed
     */
    public function getUserTechnicalEvolutionArray(int $evolution, string $type, $limit)
    {
        return $this->getUserTechnicalEvolutionNativeQuery($evolution, $type, $limit)->getArrayResult();
    }

    /**
     * @param int $evolution
     * @param string $type
     * @param $limit
     * @return array
     */
    public function getUserTechnicalEvolutionScalar(int $evolution, string $type, $limit)
    {
        return $this->getUserTechnicalEvolutionNativeQuery($evolution, $type, $limit)->getScalarResult();
    }

    /**
     * @param int $evolution
     * @param $type
     * @param $limit
     * @return \Doctrine\ORM\NativeQuery
     */
    private function getUserTechnicalEvolutionNativeQuery($evolution, $type, $limit)
    {
        # create rsm object
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult($this->getEntityName(), 'ute');
        $rsm->addFieldResult('ute', 'ute_id', 'id');
        $rsm->addFieldResult('ute', 'ute_type', 'type');
        $rsm->addFieldResult('ute', 'ute_date', 'date');
        $rsm->addFieldResult('ute', 'ute_note', 'note');
        $rsm->addFieldResult('ute', 'ute_comment', 'comment');
        $rsm->addFieldResult('ute', 'ute_user', 'id');
        $rsm->addFieldResult('ute', 'ute_technical_evolution', 'technicalEvolution');
        $rsm->addJoinedEntityResult('AppBundle\Entity\User', 'u', 'ute', 'user');
        $rsm->addFieldResult('u', 'u_id', 'id');
        $rsm->addJoinedEntityResult('AppBundle\Entity\UserProfile', 'up', 'u', 'userProfile');
        $rsm->addFieldResult('up', 'up_id', 'id');
        $rsm->addFieldResult('up', 'up_firstname', 'firstname');
        $rsm->addFieldResult('up', 'up_lastname', 'lastname');
        # set entity name
        $table = $this->getClassMetadata()->getTableName();
        # make a query
        /** @noinspection SqlResolve */
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT ute.id AS ute_id,
                ute.type,
                ute.date AS ute_date,
                ute.comment AS ute_comment,
                ute.note AS ute_note,
                ute.technical_evolution_id AS ute_techncial_evolution,
                ute.user_id AS ute_user,
                u.id AS u_id, 
                u.user_profile_id AS u_user_profile,
                up.id AS up_id,
                up.firstname AS up_firstname,
                up.lastname AS up_lastname
            FROM {$table} ute 
            INNER JOIN user u ON u.id = ute.user_id
            INNER JOIN user_profile up ON up.id = u.user_profile_id
            WHERE ute.type = '{$type}' AND ute.technical_evolution_id = {$evolution}
            ORDER BY ute.date DESC
            LIMIT {$limit}
        ", $rsm);
        return $query;
    }

    /**
     * Count nb notes by company
     *
     * @param int $te
     * @param int $company
     * @return mixed
     */
    public function countNotesByCompany(int $te, int $company)
    {
        return $this->createQueryBuilder('ute')
            ->select('COUNT(ute)')
            ->join('ute.technicalEvolution', 'te')
            ->join('ute.user', 'u')
            ->where('ute.type = :type')
            ->andWhere('te.id = :te')
            ->andWhere('u.company = :company')
            ->setParameter('type', 'note')
            ->setParameter('te', $te)
            ->setParameter('company', $company)
            ->getQuery()
            ->getSingleScalarResult();
    }
}