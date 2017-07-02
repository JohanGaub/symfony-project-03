<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getComment($ticket) {
        return $this->createQueryBuilder('c')
            ->select('c.comment as comment', 'up.firstname', 'up.lastname', 'c.creationDate', 'c.updateDate')
            ->join('c.ticket', 't')
            ->join('c.user', 'u')
            ->join('u.userProfile','up')
            ->where('c.ticket = :ticket')
            ->orderBy('c.creationDate', 'DESC')
            ->setParameter('ticket', $ticket)
            ->getQuery()
            ->getResult();
    }
}
