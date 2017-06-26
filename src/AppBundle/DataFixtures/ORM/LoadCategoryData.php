<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Category;
use Faker;

/**
 * Class LoadCategoryData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < DataParameters::NB_CATEGORY; $i++){

            $randomType = 'category_type_id_' . mt_rand(0, 3 - 1);

            $title = $faker->words(3, true);
            $description = $faker->sentences(3, true);
            $category = new Category();
            $category->setTitle($title);
            $category->setDescription($description);
            $category->setType($this->getReference($randomType));
            $em->persist($category);
            $this->setReference('category_id_' . $i , $category);
        }
        $em->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

}