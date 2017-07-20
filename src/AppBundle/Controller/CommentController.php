<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Route("/comment")
 * @Security("has_role('ROLE_FINAL_CLIENT')")
 */
class CommentController extends Controller
{
    /**
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @internal param Comment $deleteComment
     * @Route("/delete/{comment}", name="comment_delete")
     */
    public function deleteCommentAction(Comment $comment) {

        $ticket = $comment->getTicket()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

      return $this->redirectToRoute('ticket_edit', [
          'ticket' => $ticket,
      ]);
    }




}
