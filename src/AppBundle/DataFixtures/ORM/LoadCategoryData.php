<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadCategoryData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $count = 0;
        for ($i = 0; $i < 6; $i++){
        $title = $faker->words(3, true);
        $tag = $faker->word;
        $description  = $faker->sentences(3, true);
            for ($j = 0; $j <= 2; $j++) {
                $category = new Category();
                if ($j ==0 ) {
                    $type = "technical";
                } elseif ($j ==1 ) {
                    $type = "commercial";
                } else {
                    $type = "other";
                }
                $category->setTitle($title);
                $category->setTag( $tag);
                $category->setDescription($description);
                $category->setType($type);
                $count++;
                $manager->persist($category);
                $this->setReference('category_id_' . $count , $category);
            }
        }
        $manager->flush();

    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}