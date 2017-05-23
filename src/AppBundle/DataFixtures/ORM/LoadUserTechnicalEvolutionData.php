<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\UserTechnicalEvolution;
use AppBundle\DataFixtures\DataParameters;
use Faker;

/**
 * Class LoadUserTechnicalEvolutionData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserTechnicalEvolutionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $nb = new DataParameters();
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < $nb::NB_USER_TECHNICAL_EVOLUTION ; $i++)
        {
            $randomTechnical = 'technical_evolution_id_' . mt_rand(0, $nb::NB_TECHNICAL_EVOLUTION - 1);
            $randomUser = $nb->getRandomUser();

            $type = ($i % 2 == 0) ? 'note' : 'comment';

            $ute = new UserTechnicalEvolution();
            $ute->setNote(mt_rand(1, 4));
            $ute->setComment($faker->sentence(6));
            $ute->setType($type);
            $ute->setTechnicalEvolution($this->getReference($randomTechnical));
            $ute->setUser($this->getReference($randomUser));
            $em->persist($ute);

            $this->setReference('user_technical_evolution_id_' . $i, $ute);
        }
        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 8;
    }
}