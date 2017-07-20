<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadTicketData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTicketData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker                       = Faker\Factory::create('fr_FR');

        $emergencies                  = [
            0 => 'Normale',
            1 => 'Haute',
        ];


        for($i = 0 ; $i < DataParameters::NB_TICKET ; $i++)
        {
            $randomCategory             = 'category_id_' . mt_rand(0, DataParameters::NB_CATEGORY - 1);
            $randomProduct              = 'product_id_' . mt_rand(0, DataParameters::NB_PRODUCT - 1);
            $randomProjectResponsible   = 'user_project_resp_id_' . mt_rand(0, DataParameters::NB_PROJECT_RESP - 1);
            $randomStatus               = 'status_id_' . mt_rand(0, 4 - 1);
            $randomOrigin               = 'origin_id_' . mt_rand(0, 3 - 1);
            $randomTicketType           = 'ticket_type_id_' . mt_rand(0, 2 - 1);

            $emergency                  = $emergencies[mt_rand(0, count($emergencies) - 1)];

            $root                       = $this->container->get('kernel')->getRootDir(); // Necessary to upload a file

            $ticket                     = new Ticket();
            $ticket->setSubject($faker->sentence(4, true));
            $ticket->setContent($faker->sentences(mt_rand(1, 50), true));
            $ticket->setOrigin($this->getReference($randomOrigin));
            $ticket->setTicketType($this->getReference($randomTicketType));
            $ticket->setEmergency($emergency);
            $ticket->setStatus($this->getReference($randomStatus));

            $ticket->setUpload($faker->file($root . '/../web/assets/img', $root . '/../web/assets/upload', false));

            $ticket->setCreationDate($faker->dateTime);
            $ticket->setUpdateDate($faker->dateTime);
            $ticket->setEndDate($faker->dateTime);
            $ticket->setIsArchive($faker->boolean);
            $ticket->setCategory($this->getReference($randomCategory));
            $ticket->setProduct($this->getReference($randomProduct));
            $ticket->setUser($this->getReference($randomProjectResponsible));

            $this->setReference('ticket_id_' . $i,$ticket);

            $em->persist($ticket);
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
        return 9;
    }
}