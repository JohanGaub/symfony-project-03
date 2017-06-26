<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Product;
use Faker;

/**
 * Class LoadProductData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < DataParameters::NB_PRODUCT; $i++)
        {
            $product = new Product();
            $product->setName($faker->word(1, true));
            $product->setDescription($faker->sentences(7, true));
            $em->persist($product);

            $this->setReference('product_id_' . $i, $product);
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