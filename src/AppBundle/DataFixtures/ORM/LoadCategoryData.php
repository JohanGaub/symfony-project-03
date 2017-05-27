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
        $count = 0;
        for ($i = 0; $i < DataParameters::NB_CATEGORY / 3; $i++){
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
                $em->persist($category);
                $this->setReference('category_id_' . $count , $category);
                $count++;
            }
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
        return 2;
    }

}