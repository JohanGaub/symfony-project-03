<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\TechnicalEvolution;
use Faker;

/**
 * Class LoadTechnicalEvolutionData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTechnicalEvolutionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < DataParameters::NB_TECHNICAL_EVOLUTION; $i++){

            $randomUser = DataParameters::getRandomUser();
            $randomCategory = 'category_id_' . mt_rand(0, DataParameters::NB_CATEGORY - 1);
            $randomProduct = 'product_id_' . mt_rand(0, DataParameters::NB_PRODUCT - 1);

            $te= new TechnicalEvolution();
            $te->setTitle($faker->title);
            $te->setSumUp($faker->sentence(6));
            $te->setContent($faker->paragraph(7));
            $te->setReason($faker->word);
            $te->setStatus($faker->word);
            $te->setOrigin($faker->jobTitle);
            $te->setExpectedDelay($faker->dateTime);
            $te->setCreationDate($faker->dateTime);
            $te->setUpdateDate($faker->dateTime);
            $te->setUser($this->getReference($randomUser));
            $te->setCategory($this->getReference($randomCategory));
            $te->setProduct($this->getReference($randomProduct));
            $em->persist($te);

            $this->setReference('technical_evolution_id_' . $i, $te);
        }
        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 7;
    }

}