<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Faq;
use Faker;

/**
 * Class LoadFaqData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadFaqData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < DataParameters::NB_FAQ; $i++){
            $randomCategory = 'category_id_' . mt_rand(0, DataParameters::NB_CATEGORY - 1);
            $randomProduct = 'product_id_' . mt_rand(0, DataParameters::NB_PRODUCT - 1);

            $faq = new Faq();
            $faq->setTitle($faker->title);
            $faq->setSumUp($faker->sentence);
            $faq->setContent($faker->paragraph());
            $faq->setUpload($faker->sentence());
            $faq->setCreationDate($faker->dateTime);
            $faq->setCategory($this->getReference($randomCategory));
            $faq->setProduct($this->getReference($randomProduct));
            $em->persist($faq);
        }
        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }

}