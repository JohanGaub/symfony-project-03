<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTechnicalEvolution;
use AppBundle\Form\Evolution\AdminTechnicalEvolutionType;
use AppBundle\Form\Evolution\CommentUserTechnicalEvolutionType;
use AppBundle\Form\Evolution\TechnicalEvolutionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TechnicalEvolutionController
 * @package AppBundle\Controller
 * @Route("/evolution-technique", name="evolutionArea")
 */
class TechnicalEvolutionController extends Controller
{
    /**
     * List all evolution with filter
     *
     * @Route("/liste/{page}", name="evolutionHome")
     * @param int $page
     * @return Response
     */
    public function indexAction(int $page = 1)
    {
        // TODO => Transform that array in searchFormEngine ??
        $params = [
            'ct.value' => 'commercial',
        ];
        $paramsTranformers = $this->get('app.sql.search_params_getter');
        $allowParamsFormat = $paramsTranformers->setParams($params)->getParams();
        // get technical evolution repository
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        // set Pagination parameters
        $evoByPage = 9;
        $evoTotal = count($repo->getNbEvolution($allowParamsFormat));

        $pagination = [
            'page'          => $page,
            'route'         => 'evolutionHome',
            'pages_count'   => ceil($evoTotal / $evoByPage),
            'route_params'  => array(),
        ];
        $evolutions = $repo->getListEvolution($allowParamsFormat, $page, $evoByPage);

        return $this->render('AppBundle:Pages/Evolutions:indexEvolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination
        ]);
    }

    /**
     * Add new evolution
     * TODO -> need to rework on user relations
     * TODO -> mail & admin confirmation
     *
     * @Route("/nouveau", name="evolutionAdd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $te = new TechnicalEvolution();
        $form = $this->createForm(TechnicalEvolutionType::class, $te);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $dictionaryStatus = $this->getDoctrine()->getRepository('AppBundle:Dictionary')
                ->getStartingEvolutionStatus();

            $te->setCreationDate(new \DateTime('now'));
            $te->setStatus($dictionaryStatus[0]);
            $te->setCategory($form->getData()->getCategory());

            $em = $this->getDoctrine()->getManager();
            $em->persist($te);
            $em->flush();
            $this->addFlash('notice', 'Votre demande d\'évolution à bien été prise en compte !');
            return $this->redirectToRoute('evolutionHome');
        }
        return $this->render('@App/Pages/Evolutions/basicFormEvolution.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Update evolution (Admin && Basic users)
     *
     * @Route("/modification/{technicalEvolutionId}", name="evolutionUpdate")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return Response
     */
    public function updateAction(Request $request, int $technicalEvolutionId)
    {
        $em = $this->getDoctrine()->getManager();
        $te = $em->getRepository('AppBundle:TechnicalEvolution')
            ->find($technicalEvolutionId);

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(TechnicalEvolutionType::class, $te);
            $view = '@App/Pages/Evolutions/basicFormEvolution.html.twig';
        } else {
            $form = $this->createForm(AdminTechnicalEvolutionType::class, $te);
            $view = '@App/Pages/Evolutions/adminFormEvolution.html.twig';
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $te->setUpdateDate(new \DateTime('now'));
            $em->persist($te);
            $em->flush();

            $this->redirectToRoute('evolutionUnit', [
                'technicalEvolutionId' => $technicalEvolutionId
            ]);
        }
        return $this->render($view, ['form' => $form->createView()]);
    }

    /**
     * Get unit evolution with note & comment
     *
     * @Route("/{technicalEvolutionId}", name="evolutionUnit")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return Response
     */
    public function unitIndexAction(Request $request, int $technicalEvolutionId)
    {
        $doctrine = $this->getDoctrine();

        $evolution = $doctrine->getRepository('AppBundle:TechnicalEvolution')
            ->getUnitEvolution($technicalEvolutionId)[0];
        $uteRepository = $doctrine->getRepository('AppBundle:UserTechnicalEvolution');

        $comments   = $uteRepository->getUserTechnicalEvolution($evolution['te_id'], 'comment', 10);
        $notes      = $uteRepository->getUserTechnicalEvolution($evolution['te_id'], 'note', 9999999);

        $uteComment     = new UserTechnicalEvolution();
        $formComment    = $this->createForm(CommentUserTechnicalEvolutionType::class, $uteComment);
        $formComment->handleRequest($request);

        $formUpdate = $this->createForm(CommentUserTechnicalEvolutionType::class, null);
        $formUpdate->handleRequest($request);

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution' => $evolution,
            'comments'  => $comments,
            'notes'     => $notes,
            'form'      => $formComment->createView(),
            'updateForm'=> $formUpdate->createView(),
        ]);
    }

    /**
     * Add new comment for TechnicalEvolutions
     *
     * @Route("/commentaires/ajout/{technicalEvolutionId}", name="evolutionCommentsAdd")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return JsonResponse
     */
    public function addCommentsAction(Request $request, int $technicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        // data is nb element page already get
        $data['comment']    = $request->request->get('data');
        $userProfile        = $this->getUser()->getUserProfile();
        $data['user']       = $userProfile->getFirstname() . ' ' . $userProfile->getLastname();
        $data['date']       = new \DateTime('now');

        $em = $this->getDoctrine()->getManager();
        $technicalEvolution = $em->getRepository('AppBundle:TechnicalEvolution')
            ->findOneBy(['id' => $technicalEvolutionId]);

        $comment = new UserTechnicalEvolution();
        $comment->setTechnicalEvolution($technicalEvolution);
        $comment->setUser($this->getUser());
        $comment->setType('comment');
        $comment->setDate($data['date']);
        $comment->setComment($data['comment']);

        $em->persist($comment);
        $em->flush();

        $data['id'] = $comment->getId();

        return new JsonResponse($data);
    }

    /**
     * Load more comments for TechnicalEvolutions
     *
     * @Route("/commentaires/chargement/{technicalEvolutionId}", name="evolutionCommentsLoading")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return JsonResponse
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

        return new JsonResponse($comments);
    }

    /**
     * Delete comments for TechnicalEvolutions
     *
     * @Route("/commentaires/suppression/{userTechnicalEvolutionId}", name="evolutionCommentsDelete")
     * @param Request $request
     * @param int $userTechnicalEvolutionId
     * @return JsonResponse
     */
    public function deleteCommentsAction(Request $request, int $userTechnicalEvolutionId)
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
     * Update comments for TechnicalEvolutions
     *
     * @Route("/commentaires/modification/{userTechnicalEvolutionId}", name="evolutionCommentsUpdate")
     * @param Request $request
     * @param int $userTechnicalEvolutionId
     * @return JsonResponse
     */
    public function updateCommentsAction(Request $request, int $userTechnicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $data = $request->request->get('data');
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:UserTechnicalEvolution')
            ->findOneBy(['id' => $userTechnicalEvolutionId]);

        /**
         * Check if the user have a comment
         * or if user is admin
         */
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $this->getUser()->getId() == $comment->getUser()->getId()) {
            $comment->setComment($data);
            $em->persist($comment);
            $em->flush();
            return new JsonResponse($data);
        }
        throw $this->createAccessDeniedException();
    }

    /**
     * Add new note for TechnicalEvolutions
     *
     * @Route("/notes/ajout/{userTechnicalEvolutionId}", name="evolutionCommentsUpdate")
     * @param Request $request
     * @param int $userTechnicalEvolutionId
     * @return JsonResponse
     */
    public function addNoteAction(Request $request, int $userTechnicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $em = $this->getDoctrine()->getManager();
        $note = $request->request->get('data');
        $user = $this->getUser();
        $company = $user->getCompany();
        $users = $em->getRepository('AppBundle:User')->find($company);


        return new JsonResponse($users);
    }

    /**
     * Get full evolution have status
     *
     * @Route("/en-attente/liste", name="evolutionWaiting")
     * @return Response
     */
    public function adminListWaitingAction()
    {
        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getListWaitingEvolution();

        return $this->render('@App/Pages/Evolutions/waitingEvolution.html.twig', [
            'evolutions' => $evolutions,
        ]);
    }

    /**
     * Do action in evolution have status 'En attente'
     * Validate Or Closest action
     *
     * @Route("/en-attente/traitement/{technicalEvolutionId}", name="evolutionAdminWorks")
     * @param Request $request
     * @param int $technicalEvolutionId
     * @return JsonResponse
     */
    public function adminWaitingWorksAction(Request $request, int $technicalEvolutionId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $data   = $request->request->get('data');
        $em     = $this->getDoctrine()->getManager();

        if ($data) {
            // if validate action
            $dictionary = $em->getRepository('AppBundle:Dictionary')
                ->findOneBy(['value' => 'En cours']);
        } else {
            // else to bad for validate
            $dictionary = $em->getRepository('AppBundle:Dictionary')
                ->findOneBy(['value' => 'Refusé']);
        }
        $evolution = $em->getRepository('AppBundle:TechnicalEvolution')
            ->find($technicalEvolutionId);
        $evolution->setStatus($dictionary);

        $em->persist($evolution);
        $em->flush();

        return new JsonResponse('Valid XmlHttp request !');
    }

}
