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
            'technical',
            'commercial',
            'other',
        ];

        $teStatus = [
            'En cours',
            'En attente',
            'Fermé',
            'Annulé',
        ];

        $teOrigin = [
            'Responsable projet',
            'Technicien',
            'Commercial',
        ];

        for ($i = 0; $i < count($categoryType); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('category_type');
            $dictionary->setValue($categoryType[$i]);
            $em->persist($dictionary);
            $this->setReference('category_type_id_' . $i, $dictionary);
        }

        for ($i = 0; $i < count($teStatus); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('technical_evolution_status');
            $dictionary->setValue($teStatus[$i]);
            $em->persist($dictionary);
            $this->setReference('technical_evolution_status_id_' . $i, $dictionary);
        }

        for ($i = 0; $i < count($teOrigin); ++$i){
            $dictionary = new Dictionary();
            $dictionary->setType('technical_evolution_origin');
            $dictionary->setValue($teOrigin[$i]);
            $em->persist($dictionary);
            $this->setReference('technical_evolution_origin_id_' . $i, $dictionary);
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