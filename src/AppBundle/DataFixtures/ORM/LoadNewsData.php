<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\News;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use Faker;

/**
 * Class LoadNewsData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < DataParameters::NB_NEWS; $i++){
            $randomType = 'category_type_id_' . mt_rand(0, 3 - 1);

            $new = new News();
            $new->setType($this->getReference($randomType));
            $new->setCreationDate($faker->dateTime);
            $new->setTitle($faker->words(5, true));
            $new->setContent($faker->sentence(22, true));
            $new->setIsVisible($i % 2 == 0 ? true : false);
            $em->persist($new);
        }
        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 11;
    }

}