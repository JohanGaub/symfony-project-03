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
            LIMIT {$limit}
        ", $rsm);

        return $query;
    }
}
