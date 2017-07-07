<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

/**
 * Class LoadCommentData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker                  = Faker\Factory::create('fr_FR');

        for($i = 0 ; $i < DataParameters::NB_COMMENT -1 ; $i++) {

            $randomResponsibleUser  = DataParameters::getRandomResponsibleUser();
            $randomTicket           = 'ticket_id_' . mt_rand(0, DataParameters::NB_TICKET - 1);

            $comment                = new Comment();
            $comment->setComment($faker->sentences(10, true));
            $comment->setCreationDate($faker->dateTime);
            $comment->setUpdateDate($faker->dateTime);
            $comment->setUser($this->getReference($randomResponsibleUser));
            $comment->setTicket($this->getReference($randomTicket));

            $this->setReference('comment_id_' . $i, $comment);

            $em->persist($comment);
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
        return 10;
    }

}