<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Entity\User;
use AppBundle\Form\Evolution\NoteUserTechnicalEvolutionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\UserTechnicalEvolution;
use AppBundle\Form\Evolution\CommentUserTechnicalEvolutionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UserTechnicalEvolutionController
 * @package AppBundle\Controller
 * @Route("/evolution-technique")
 */
class UserTechnicalEvolutionController extends Controller
{
    /**
     * Add new comment for TechnicalEvolutions
     *
     * @Route("/commentaires/ajout/{technicalEvolution}", name="evolutionCommentsAdd")
     * @param Request $request
     * @param TechnicalEvolution $technicalEvolution
     * @return JsonResponse
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function addCommentsAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $comment = new UserTechnicalEvolution('comment');
        $form = $this->createForm(CommentUserTechnicalEvolutionType::class, $comment);
        $form->handleRequest($request);
        $data = [];

        if ($form->isValid() && $technicalEvolution->getStatus()->getValue() != 'En attente') {
            $user = $this->getUser();
            $currentDate = new \DateTime('now');
            $comment->setUser($user);
            $comment->setTechnicalEvolution($technicalEvolution);
            $comment->setDate($currentDate);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $userProfile = $user->getUserProfile();
            $data = [
                'id'      => $comment->getId(),
                'user'    => $userProfile->getFullName(),
                'date'    => $currentDate,
                'comment' => $comment->getComment()
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * Load more comments for TechnicalEvolutions
     *
     * @Route("/commentaires/chargement/{technicalEvolutionId}", name="evolutionCommentsLoading")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return JsonResponse
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function loadMoreCommentsAction(Request $request, int $technicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        // data is nb element page already get
        $data = $request->request->get('data');
        $comments = $this->getDoctrine()->getRepository('AppBundle:UserTechnicalEvolution')
            ->getUserTechnicalEvolutionArray($technicalEvolutionId, 'comment', "$data, 10");
        if (count($comments) == 0) {
            throw new Exception('No comment are find !');
        }
        return new JsonResponse($comments);
    }

    /**
     * Update comments for TechnicalEvolutions
     *
     * @Route("/commentaires/modification/{userTechnicalEvolution}", name="evolutionCommentsUpdate")
     * @param Request $request
     * @param UserTechnicalEvolution $userTechnicalEvolution
     * @return JsonResponse
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function updateCommentsAction(Request $request, UserTechnicalEvolution $userTechnicalEvolution)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $comment = $userTechnicalEvolution;
        $form = $this->createForm(CommentUserTechnicalEvolutionType::class, $comment);
        $form->handleRequest($request);
        $data = [];

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $this->getUser()->getId() == $comment->getUser()->getId()) {
            if ($form->isValid()) {
                $user = $this->getUser();
                $currentDate = new \DateTime('now');
                $comment->setUpdateDate($currentDate);
                $userProfile = $user->getUserProfile();
                $data = [
                    'user'    => $userProfile->getFirstname() . ' ' . $userProfile->getLastname(),
                    'date'    => $currentDate,
                    'comment' => $comment->getComment()
                ];
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
            }
            return new JsonResponse($data);
        }
        throw $this->createAccessDeniedException();
    }

    /**
     * Delete comments for TechnicalEvolutions
     *
     * @Route("/commentaires/suppression/{userTechnicalEvolutionId}", name="evolutionCommentsDelete")
     * @param Request $request
     * @param mixed $userTechnicalEvolutionId
     * @return JsonResponse
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function deleteCommentsAction(Request $request, $userTechnicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:UserTechnicalEvolution')
            ->findOneBy(['id' => $userTechnicalEvolutionId]);

        /**
         * Here we do delete only if user is admin or if
         * userTechnicalEvolution->user_id is current user id
         */
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $this->getUser()->getId() == $comment->getUser()->getId()) {
            $em->remove($comment);
            $em->flush();
            return new JsonResponse('Suppression reussie !');
        }
        throw $this->createAccessDeniedException();
    }

    /**
     * Add new note for TechnicalEvolutions
     *
     * @Route("/notes/ajout/{technicalEvolution}", name="evolutionNoteAdd")
     * @param Request $request
     * @param TechnicalEvolution $technicalEvolution
     * @return JsonResponse
     * @Security("has_role('ROLE_PROJECT_RESP')")
     */
    public function addNoteAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }

        $note = new UserTechnicalEvolution('note');
        $form = $this->createForm(NoteUserTechnicalEvolutionType::class, $note);
        $form->handleRequest($request);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $uteRepository = $em->getRepository('AppBundle:UserTechnicalEvolution');

        $nbNotes = $uteRepository->countNotesByCompany(
            $technicalEvolution->getId(),
            $this->getUser()->getCompany()->getId()
        );

        if ($nbNotes == 0) {
            if ($form->isValid()) {
                $currentDate = new \DateTime('now');
                $note->setNote($form['note']->getData());
                $note->setUser($user);
                $note->setTechnicalEvolution($technicalEvolution);
                $note->setDate($currentDate);
                $em->persist($note);
                $em->flush();
            }
            return new JsonResponse();
        }


        $userVote = $uteRepository->findOneBy([
            'user'               => $user,
            'technicalEvolution' => $technicalEvolution,
            'type'               => 'note'
        ]);

            if ($userVote) {
                $userVote->setNote($form['note']->getData());
                $currentDate = new\DateTime('now');
                $userVote->setUpdateDate($currentDate);
                $em->persist($userVote);
                $em->flush();

            }
            return new JsonResponse();
        }

}
