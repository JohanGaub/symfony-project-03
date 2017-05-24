<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UserProfile;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\User;
use Faker;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param $faker
     * @return UserProfile
     */
    private function setUserProfile($faker)
    {
        // -> Profile
        $profile = new UserProfile();
        $profile->setFirstname($faker->word);
        $profile->setLastname($faker->word);
        $profile->setPhone1($faker->phoneNumber);
        $profile->setPhone2($faker->phoneNumber);

        return $profile;
    }

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $nb = new DataParameters();
        $faker = Faker\Factory::create('fr_FR');

        /**
         * SuperAdmin
         */
        for ($i = 0; $i < $nb::NB_SUPER_ADMIN; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('superadmin' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_super_admin_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        /**
         * Admin
         */
        for ($i = 0; $i < $nb::NB_ADMIN; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('admin' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_admin_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        /**
         * ProjetctResp
         */
        for ($i = 0; $i < $nb::NB_PROJECT_RESP; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('projresp' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_PROJET_RESP']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_projet_resp_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        /**
         * Technician
         */
        for ($i = 0; $i < $nb::NB_TECHNICIAN; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('technician' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_TECHNICIAN']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_technician_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        /**
         * Commercial
         */
        for ($i = 0; $i < $nb::NB_COMMERCIAL; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('commercial' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_COMMERCIAL']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_commercial_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        /**
         * Final Client
         */
        for ($i = 0; $i < $nb::NB_FINAL_CLIENT; $i++){
            $randomCompany = 'company_id_' . mt_rand(0, $nb::NB_COMPANY - 1);
            $user = new User();
            $user->setEmail('finalclient' . $i . '@test.fr');
            $user->setPassword(password_hash("pass", PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(1);
            $profile = $this->setUserProfile($faker);
            $user->setUserProfile($profile);
            $user->setCompany($this->getReference($randomCompany));
            $this->setReference('user_final_client_id_' . $i, $user);
            $em->persist($user);
            $em->persist($profile);
        }

        $em->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }

}