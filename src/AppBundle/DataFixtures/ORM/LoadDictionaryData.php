<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Dictionary;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadDictionaryData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadDictionaryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $categoryType = [
            'Technique',
            'Commerciale',
            'Autre'
        ];
        $evolutionStatus = [
            'En cours',
            'Achevé',
            'En attente',
            'Fermé'
        ];
        $status = [
            'En cours',
            'Résolu',
            'En attente',
            'Fermé'
        ];
        $origin = [
            'Super administrateur',
            'Administrateur',
            'Responsable projet',
            'Technicien',
            'Commercial'
        ];
        $ticketType = [
            'Résolution de bug',
            'Autre'
        ];

        for ($i = 0; $i < count($categoryType); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('category_type');
            $dictionary->setValue($categoryType[$i]);
            $em->persist($dictionary);
            $this->setReference('category_type_id_' . $i, $dictionary);
        }
        for ($i = 0; $i < count($evolutionStatus); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('evolution_status');
            $dictionary->setValue($status[$i]);
            $em->persist($dictionary);
            $this->setReference('evolution_status_id_' . $i, $dictionary);
        }
        for ($i = 0; $i < count($status); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('status');
            $dictionary->setValue($status[$i]);
            $em->persist($dictionary);
            $this->setReference('status_id_' . $i, $dictionary);
        }
        for ($i = 0; $i < count($origin); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('origin');
            $dictionary->setValue($origin[$i]);
            $em->persist($dictionary);
            $this->setReference('origin_id_' . $i, $dictionary);
        }
        for ($i = 0; $i < count($ticketType); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('ticket_type');
            $dictionary->setValue($ticketType[$i]);
            $em->persist($dictionary);
            $this->setReference('ticket_type_id_' . $i, $dictionary);
        }

        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

}