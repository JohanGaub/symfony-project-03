<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class DictionaryVerification
 * @package AppBundle\Service
 */
class DictionaryVerification
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * DictionaryVerification constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Function return array of element was same field => id
     * @param string $type
     * @param int $id
     * @return array
     */
    public function getAllowedAction(string $type, int $id)
    {
        $entity = '';
        $field  = '';

        if ($type == 'category_type') {
            $entity = 'AppBundle:Category';
            $field  = 'type';
        } else if ($type == 'origin') {
            $entity = 'AppBundle:TechnicalEvolution';
            $field  = 'origin';
        } else if ($type == 'ticket_status') {
            $entity = 'AppBundle:Ticket';
            $field  = 'status';
        } else if ($type == 'evolution_status') {
            $entity = 'AppBundle:TechnicalEvolution';
            $field  = 'status';
        } else if ($type == 'ticket_type') {
            $entity = 'AppBundle:Ticket';
            $field  = 'ticketType';
        }
        return $this->em->getRepository($entity)->findBy([$field => $id]);
    }
}