<?php
/**
 * Created by PhpStorm.
 * User: wilder
 * Date: 29/05/17
 * Time: 22:47
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\DataParameters;
use AppBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

/**
 * Class LoadTicketData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTicketData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $faker                      = Faker\Factory::create('fr_FR');
        $origin                     = getUser()->getRoles(); // A VOIR
        $randomCategory             = 'category_id_' . mt_rand(0, DataParameters::NB_CATEGORY - 1);
        $randomProduct              = 'product_id_' . mt_rand(0, DataParameters::NB_PRODUCT - 1);
        $randomProjectResponsible   = 'user_project_resp_id_' . mt_rand(0, DataParameters::NB_PROJECT_RESP - 1);

        $emergency      = [
            'Haute',
            'Moyenne',
            'Basse',
        ];
        $status         = [
            'reçu',
            'en cours de traitement',
            'traité',
            'archivé',
        ];

        for($i = 0 ; $i < DataParameters::NB_TICKET ; $i++);
        {
            $ticket = new Ticket();
            $ticket->setSubject($faker->sentences(7, true));
            $ticket->setContent($faker->sentences(mt_rand(1, 50), true)); // A VOIR
            $ticket->setOrigin($origin);
            $ticket->setEmergency($emergency);
            $ticket->setStatus($status);
            $ticket->setUpload($faker->file('web/assets/img', 'web/assets/uploadtest'));
            $ticket->setCreationDate($faker->dateTime);
            $ticket->setUpdateDate($faker->dateTime);
            $ticket->setEndDate($faker->dateTime);
            $ticket->setCategory($faker->getReference($randomCategory));
            $ticket->setProduct($faker->getReference($randomProduct));
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