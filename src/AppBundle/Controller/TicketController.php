<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\Ticket\EditTicketType;
use AppBundle\Form\Ticket\AddTicketType;
use AppBundle\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class TicketController
 * @package AppBundle\Controller
 * @Route("/ticket", name="ticket")
 */
class TicketController extends Controller
{

    /**
     * @return Response
     * @internal param $name
     * @Route("/", name="index_ticket")
     */
    public function indexAction()
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')->findAll();
        return $this->render('@App/Ticket/ticket.html.twig', [
            'tickets' => $tickets,]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/add", name="add_ticket")
     */
    public function addAction(Request $request)
    {
        $user       = $this->getUser();             // I get the user from User Entity to set it in the database (see upstairs)

        $ticket     = new Ticket();
        $em         = $this->getDoctrine()->getManager();
        $form       = $this->createForm(AddTicketType::class, $ticket);
//        $test = '';

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded files
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $ticket->getUpload();

//            $test = $form->getData();

            if($file != null)
            {
                // Generate a unique filename before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the uploaded files directory
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
                // Update the 'files' property to store the file name
                // instead of its contents
                $ticket->setUpload($fileName);
            }

            $ticket->setCreationDate(new \DateTime('NOW')); // To set the default creationDate to NOW
            $ticket->setStatus('En attente'); // To set the default status to "En attente"
            $ticket->setIsArchive(false); // To set the default to NOT archived
            $ticket->setUser($user); // I got (see upstairs) the user and set it hear.


            $em->persist($ticket);
            $em->flush();
            return $this->redirectToRoute('index_ticket');
        }
        return $this->render('@App/Ticket/addTicket.html.twig',[
            'form' => $form->createView(),
//            'test' => $test,
            ]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return RedirectResponse|Response
     * @Route("/edit/{ticket}", name="edit_ticket")
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        $em     = $this->getDoctrine()->getManager();
        $form   = $this->createForm(EditTicketType::class, $ticket);

        $informations = $em->getRepository('AppBundle:Ticket')->find($ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $ticket->setUpdateDate(new \DateTime('NOW')); // To set the default UpdateDate to NOW

            // To manage the endDate when you chose one particular status
            $status = $ticket->getStatus();
            if($status == 'Fermé' or $status == 'Résolu'){
                $ticket->setEndDate(new \DateTime('NOW'));
            } else {
                $ticket->setEndDate(null);
            }

            $em->flush();
            return $this->redirectToRoute('index_ticket');
        }

        return $this->render('@App/Ticket/editTicket.html.twig',[
            'informations' => $informations,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Ticket $ticket
     * @return RedirectResponse
     * @Route("/delete/{ticket}", name="delete_ticket")
     */
    public function deleteAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ticket);
        $em->flush();
        return $this->redirectToRoute('index_ticket');
    }
}
