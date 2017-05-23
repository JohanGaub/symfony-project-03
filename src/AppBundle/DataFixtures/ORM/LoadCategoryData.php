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
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $nb = new DataParameters();
        $faker = Faker\Factory::create();

        for ($i = 0; $i < $nb::NB_CATEGORY; $i++)
        {
            $type = ($i % 2 == 0) ? 'technical' : 'commercial';
            $category = new Category();
            $category->setTitle($faker->words(1, true));
            $category->setTag($faker->words(1, true));
            $category->setDescription($faker->sentences(7, true));
            $category->setType($type);
            $em->persist($category);

            $this->setReference('category_id_' . $i, $category);
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