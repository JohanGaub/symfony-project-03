<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\SearchTicketType;
use AppBundle\Form\Ticket\UpdateTicketType;
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
     * @param int $page
     * @return Response
     * @internal param $page
     * @Route("/index/{page}", name="index_ticket")
     */
    public function indexAction($page = 1)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $maxTickets = 10;
        $tickets_count = $repo->countTicketTotal();

        $pagination = [
            'page' => $page,
            'route' => 'index_ticket',
            'pages_count' => ceil($tickets_count / $maxTickets),
            'route_params' => [],
        ];

        $tickets = $repo->getList($page, $maxTickets);

        return $this->render('@App/Pages/Ticket/ticket.html.twig',[
            'tickets' => $tickets,
            'pagination' => $pagination,
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function searchAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $search = $repo->getSearch();

        $form       = $this->createForm(SearchTicketType::class, $search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('index_ticket');
        }

        return $this->render('@App/Ticket/ticket.html.twig',[
            'form' => $form->createview(),
        ]);
    }



    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/add", name="add_ticket")
     */
    public function addAction(Request $request)
    {
        $user       = $this->getUser();

        $ticket     = new Ticket();
        $em         = $this->getDoctrine()->getManager();

        $form       = $this->createForm(AddTicketType::class, $ticket);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded files
            /** @var File $file */
            $file = $ticket->getUpload();

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

            $ticket->setUser($user);

            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('index_ticket');
        }
        return $this->render('@App/Pages/Ticket/addTicket.html.twig',[
            'form' => $form->createView(),
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

        $informations = $em->getRepository('AppBundle:Ticket')->find($ticket);
        $form   = $this->createForm(EditTicketType::class, $ticket);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $ticket->setUpdateDate(new \DateTime('NOW'));

            $status = $ticket->getStatus();
            $endDate = $ticket->getEndDate();

            // To make the endDate impossible to change when you already have one with either "Fermé" status or "Résolu" status
            if(!($endDate != null and ($status == 'Fermé' or  $status == 'Résolu'))){
                if($status == 'Fermé' or $status == 'Résolu'){
                    $ticket->setEndDate(new \DateTime('NOW'));
                } else {
                    $ticket->setEndDate(null);
                }
            }
            $em->flush();
            return $this->redirectToRoute('index_ticket');
        }
        return $this->render('@App/Pages/Ticket/editTicket.html.twig',[
            'informations' => $informations,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return RedirectResponse|Response
     * @Route("/update/{ticket}", name="update_ticket")
     */
    public function updateAction(Request $request, Ticket $ticket)
    {
        $em     = $this->getDoctrine()->getManager();
        $form   = $this->createForm(UpdateTicketType::class, $ticket);

        $informations = $em->getRepository('AppBundle:Ticket')->find($ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('index_ticket');
        }
        return $this->render('@App/Pages/Ticket/updateTicket.html.twig',[
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


