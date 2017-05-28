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
        $faker = Faker\Factory::create('fr_FR');
        $loop = 0;
        $type = '';

        for($i = 0; $i < DataParameters::NB_USER_TECHNICAL_EVOLUTION ; $i++)
        {
            $randomTechnical = 'technical_evolution_id_' . mt_rand(0, DataParameters::NB_TECHNICAL_EVOLUTION - 1);
            $randomUser = DataParameters::getRandomUser();

            $ute = new UserTechnicalEvolution();

            if ($i % 2 == 0){
                $type = 'comment';
                $ute->setComment($faker->sentence(6));
            } else {
                $type = 'note';
                $ute->setNote(mt_rand(1, 10));
            }
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