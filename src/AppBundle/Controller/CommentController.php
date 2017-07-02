<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Route("/comment")
 */
class CommentController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/add", name="comment_add")
     */
    public function addCommentAction(Request $request) {
        $user = $this->getUser();
        $newComment = new Comment();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $newComment->setUser($user);

            $em->persist($newComment);
            $em->flush();
        }
        return $this->render('@App/Pages/Ticket/addComment.html.twig', [
            'new_comment'   => $newComment,
            'form_comment'  => $form->createView(),
        ]);

    }


}
