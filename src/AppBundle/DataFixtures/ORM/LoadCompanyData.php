<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Company;
use Faker;

/**
 * Class LoadCompanyData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < DataParameters::NB_COMPANY ; $i++)
        {
            $company = new Company();
            $company->setName($faker->company);
            $company->setAddress($faker->streetAddress);
            $company->setTown($faker->city);
            $company->setPostCode(mt_rand(00000,99999));
            $company->setEmail($faker->email);
            $company->setPhone(mt_rand(0000000000,9999999999));
            $company->setSiret(mt_rand(00000000000000,99999999999999));
            $em->persist($company);

            $this->setReference('company_id_' . $i, $company);
        }
        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }

}