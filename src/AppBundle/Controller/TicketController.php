<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TicketController
 * @package AppBundle\Controller
 */
class TicketController extends Controller
{

    /**
     * @param $name
     * @return Response
     * @Route("/ticket", name="ticket")
     */

    public function indexAction($name)
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')->findAll();
        return $this->render('@App/Ticket/ticket.html.twig', [
            'tickets' => $tickets,]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/ticket/add", name="add_ticket")
     */
    public function addAction(Request $request)
    {
        $ticket     = new Ticket();
        $em         = $this->getDoctrine()->getManager();
        $form       = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($ticket);
            $em->flush();
            return $this->redirectToRoute('ticket');
        }
        return $this->render('@App/Ticket/addTicket.html.twig',['form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return RedirectResponse|Response
     * @Route("/ticket/edit", name="edit_category")
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        $em     = $this->getDoctrine()->getManager();
        $form   = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('ticket');
        }

    return $this->render('@App/Ticket/addTicket.html.twig',[
        'form' => $form->createView(),
    ]);
    }

    /**
     * @param Ticket $ticket
     * @return RedirectResponse
     * @Route("/ticket/delete", name="delete_ticket")
     */
    public function deleteAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ticket);
        $em->flush();
        return $this->redirectToRoute('ticket');
    }
}
