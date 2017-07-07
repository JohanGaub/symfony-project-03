<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Ticket;
use AppBundle\Form\SearchTicketType;
use AppBundle\Form\Ticket\AddCommentType;
use AppBundle\Form\Ticket\TicketFilterType;
use AppBundle\Form\Ticket\UpdateTicketType;
use AppBundle\Form\Ticket\EditTicketType;
use AppBundle\Form\Ticket\AddTicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/index", name="ticket_index", requirements={"page" : "\d+"})
     * @Method({"post", "get"})
     * @internal param Request $request
     */
    public function indexAction()
    {
        $navigator      = $this->get("communit.navigator");

        $filter         = $navigator->getEntityFilter();

        $searchForm     = $this->createForm(TicketFilterType::class, $filter);

        return $this->render('@App/Pages/Ticket/ticket.html.twig',[
            /*** Ticket search ***/
            'data'           => $this->get("communit.navigator"),
            'filter'        => $filter,
            'filterURL'     =>http_build_query($filter),
            'documentType'  => "Ticket",
            'searchForm'    => $searchForm->createView(),
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
            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('@App/Pages/Ticket/ticket.html.twig',[
            'form' => $form->createview(),
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/add", name="ticket_add")
     */
    public function addAction(Request $request)
    {
        $user       = $this->getUser();

        $ticket     = new Ticket();
        $em         = $this->getDoctrine()->getManager();

        $addTicketForm       = $this->createForm(AddTicketType::class, $ticket);
        $addTicketForm->handleRequest($request);
        if($addTicketForm->isSubmitted() && $addTicketForm->isValid()) {
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

            return $this->redirectToRoute('ticket_index');
        }
        return $this->render('@App/Pages/Ticket/addTicket.html.twig',[
            'addTicketForm' => $addTicketForm->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return RedirectResponse|Response
     * @Route("/edit/{ticket}", name="ticket_edit")
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        /*** Edit ticket part ***/

        $em             = $this->getDoctrine()->getManager();

        $editTicketForm = $this->createForm(EditTicketType::class, $ticket);
        $editTicketForm->handleRequest($request);
        if($editTicketForm->isSubmitted() && $editTicketForm->isValid()) {

            $ticket->setUpdateDate(new \DateTime('NOW'));

            $status     = $ticket->getStatus();
            $endDate    = $ticket->getEndDate();

            // To make the endDate impossible to change when you already have one with either "Fermé" status or "Résolu" status
            if(!($endDate != null and ($status == 'Fermé' or  $status == 'Résolu'))){
                if($status == 'Fermé' or $status == 'Résolu'){
                    $ticket->setEndDate(new \DateTime('NOW'));
                } else {
                    $ticket->setEndDate(null);
                }
            }
            $em->flush();
            return $this->redirectToRoute('ticket_index');
        }

        /** TODO : refactoring for dry convention */
        /*** Add comment part ***/
        $user               = $this->getUser();
        $addComment         = new Comment();
        $addCommentForm     = $this->createForm(AddCommentType::class, $addComment);
        $addCommentForm->handleRequest($request);
        if($addCommentForm->isSubmitted() and $addCommentForm->isValid()) {
            $addComment->setUser($user);
            $addComment->setTicket($ticket);
            $addComment->setCreationDate( new \DateTime('NOW'));

            $em->persist($addComment);
            $em->flush();
            $addComment     = new Comment();
            $addCommentForm = $this->createForm(AddCommentType::class, $addComment);
        }

        $comments = $em->getRepository('AppBundle:Comment')->getComment($ticket);
        return $this->render('@App/Pages/Ticket/editTicket.html.twig',[
            /*** Edit ticket fields ***/
            'ticket'            => $ticket,
            'comments'          => $comments,
            'editTicketForm'    => $editTicketForm->createView(),

            /*** Add comment fields ***/
            'addCommentForm'    => $addCommentForm->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return RedirectResponse|Response
     * @Route("/update/{ticket}", name="ticket_update")
     */
    public function updateAction(Request $request, Ticket $ticket)
    {
        $em                 = $this->getDoctrine()->getManager();
        $updateTicketForm   = $this->createForm(UpdateTicketType::class, $ticket);

        $updateTicketForm->handleRequest($request);

        $category           = $ticket->getCategory();
        if(isset($category)) {
            $categoryType = $category->getType();
        } else {
            $categoryType = null;
        };

        $categories = $em->getRepository(Category::class)
            ->getCategoryByType($categoryType)->getQuery()->getResult();
        $categoryTypes = $em->getRepository(Dictionary::class)
            ->getItemListByType('category_type')->getQuery()->getResult();


        if($updateTicketForm->isSubmitted() && $updateTicketForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('ticket_index');
        }
        return $this->render('@App/Pages/Ticket/updateTicket.html.twig',[
            'ticket'            => $ticket,
            'updateTicketForm'  => $updateTicketForm->createView(),
            'categories'        => $categories,
            'categoryTypes'     => $categoryTypes,
            'categoryId'        => isset($category) ? $category->getId() : null,
            'categoryType'      => isset($categoryType) ? $categoryType->getId() : null,
        ]);
    }


    /**
     * @param Ticket $ticket
     * @return RedirectResponse
     * @Route("/delete/{ticket}", name="ticket_delete")
     */
    public function deleteAction(Ticket $ticket)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ticket);
        $em->flush();
        return $this->redirectToRoute('ticket_index');
    }
}


