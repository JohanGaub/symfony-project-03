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
use Symfony\Component\Finder\Finder;


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
        $origins                     = [
            0 => 'Super Administrateur',
            1 => 'Administrateur',
            2 => 'Responsable projet',
            3 => 'Technicien',
            4 => 'Commercial',
            5 => 'Client final',
        ];
        $emergencies                  = [
            0 => 'Haute',
            1 => 'Moyenne',
            2 => 'Basse',
        ];
        $status                       = [
            0 => 'en attente',
            1 => 'en cours',
            2 => 'résolu',
            3 => 'annulé',
            4 => 'non résolu',
            5 => 'archivé',
        ];

        for($i = 0 ; $i < DataParameters::NB_TICKET ; $i++)
        {
            $randomCategory             = 'category_id_' . mt_rand(0, DataParameters::NB_CATEGORY - 1);
            $randomProduct              = 'product_id_' . mt_rand(0, DataParameters::NB_PRODUCT - 1);
            $randomProjectResponsible   = 'user_project_resp_id_' . mt_rand(0, DataParameters::NB_PROJECT_RESP - 1);

            $origin                     = $origins[mt_rand(0, count($origins) - 1)];
            $emergency                  = $emergencies[mt_rand(0, count($emergencies) - 1)];
            $oneStatus                  = $status[mt_rand(0, count($status) - 1)];

            $root                       = $this->container->get('kernel')->getRootDir();

            $ticket                     = new Ticket();
            $ticket->setSubject($faker->sentences(7, true));
            $ticket->setContent($faker->sentences(mt_rand(1, 50), true));
            $ticket->setOrigin($origin);
            $ticket->setEmergency($emergency);
            $ticket->setStatus($oneStatus);

            $ticket->setUpload($faker->file($root . '/../web/assets/img',$root . '/../web/assets/uploadtest'));

            $ticket->setCreationDate($faker->dateTime);
            $ticket->setUpdateDate($faker->dateTime);
            $ticket->setEndDate($faker->dateTime);
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