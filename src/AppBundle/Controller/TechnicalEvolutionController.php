<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Form\Evolution\TechnicalEvolutionFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Evolution\NoteUserTechnicalEvolutionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\UserTechnicalEvolution;
use AppBundle\Form\Evolution\AdminTechnicalEvolutionType;
use AppBundle\Form\Evolution\CommentUserTechnicalEvolutionType;
use AppBundle\Form\Evolution\TechnicalEvolutionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TechnicalEvolutionController
 * @package AppBundle\Controller
 * @Route("/evolution-technique")
 */
class TechnicalEvolutionController extends Controller
{
    /**
     * List all evolution with filter
     *
     * @Route("/liste", name="evolutionHome")
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     * @Method({"get", "post"})
     */
    public function indexAction()
    {
        $navigator  = $this->get("app.navigator");
        $filter     = $navigator->getEntityFilter();
        $form       = $this->createForm(TechnicalEvolutionFilterType::class, $filter);

        /**
         * Next you need to render view with this element :
         * You can replace "$this->get("app.navigator")" by "$navigator"
         */
        return $this->render('@App/Pages/Evolutions/indexEvolution.html.twig', [
            'filter'        => $filter,
            'filterURL'     => http_build_query($filter->getArray()),
            'data'          => $this->get("app.navigator"),
            'documentType'  => "Evolutions",
            'form'          => $form->createView(),
        ]);
    }

    /**
     * Add new evolution
     *
     * @Route("/nouveau", name="evolutionAdd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function addAction(Request $request)
    {
        $te = new TechnicalEvolution();
        $form = $this->createForm(TechnicalEvolutionType::class, $te);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $dictionaryStatus = $em->getRepository('AppBundle:Dictionary')->findOneBy([
                'type' => 'evolution_status',
                'value' => 'En attente'
            ]);

            $te->setCreationDate(new \DateTime('now'));
            $te->setStatus($dictionaryStatus);
            $te->setUser($this->getUser());
            $te->setCategory($form->getData()->getCategory());
            $te->setIsArchivate(false);

            $em->persist($te);
            $em->flush();

            /**
             * Mailling part (service)
             */
            $this->get('app.email.sending')->sendEmail(
                'Une nouvelle évolution technique vient d\'arrivée',
                $this->get('app.getter_user_admin')->getAdmin(),
                $this->render('@App/Email/email.newEvolution.html.twig', [
                    'url' => $this->generateUrl('evolutionUnit', [
                        'technicalEvolution' => $te->getId()
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                    'evolution' => $te
                ])
            );
            $this->addFlash('notice', 'Votre évolution à bien été ajouté !');
            return $this->redirectToRoute('evolutionUser');
        }
        return $this->render('@App/Pages/Evolutions/basicFormEvolution.html.twig', [
            'form'      => $form->createView(),
            'titlePage' => 'Nouvelle évolution',
        ]);
    }

    /**
     * Update evolution (Admin && Basic users)
     *
     * @Route("/modification/{technicalEvolution}", name="evolutionUpdate")
     * @param Request $request
     * @param TechnicalEvolution $technicalEvolution
     * @return Response
     * @Security("has_role('ROLE_PROJECT_RESP')")
     */
    public function updateAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        /** Verification about user, if he is admin or not */
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(TechnicalEvolutionType::class, $technicalEvolution);
            $view = '@App/Pages/Evolutions/basicFormEvolution.html.twig';
        } else {
            $form = $this->createForm(AdminTechnicalEvolutionType::class, $technicalEvolution);
            $view = '@App/Pages/Evolutions/adminFormEvolution.html.twig';
        }
        $form->handleRequest($request);

        $em             = $this->getDoctrine()->getManager();
        $category       = $technicalEvolution->getCategory();
        $categoryType   = isset($category) ? $category->getType() : null;

        $categories = $em->getRepository('AppBundle:Category')
            ->getCategoryByTypeResult($categoryType);
        $categoryTypes = $em->getRepository('AppBundle:Dictionary')
            ->getItemListByTypeResult('category_type');

        if ($form->isSubmitted() && $form->isValid()) {
            $technicalEvolution->setUpdateDate(new \DateTime('now'));
            $em->persist($technicalEvolution);
            $em->flush();

            /**
             * Mailling part (service)
             */
            $this->get('app.email.sending')->sendEmail(
                'Votre évolution vient d\'être modifié',
                [$this->getUser()],
                $this->render('@App/Email/email.updateEvolution.html.twig', [
                    'url' => $this->generateUrl('evolutionUnit', [
                        'technicalEvolution' => $technicalEvolution->getId()
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                    'evolution' => $technicalEvolution
                ])
            );

            $this->addFlash('notice', 'Votre modification a bien été prise en compte');

            return $this->redirectToRoute('evolutionUnit', [
                'technicalEvolution' => $technicalEvolution->getId()
            ]);
        }

        return $this->render($view, [
            'form'          => $form->createView(),
            'categoryId'    => isset($category) ? $category->getId() : null,
            'categoryType'  => isset($categoryType) ? $categoryType->getId() : null,
            'categories'     => $categories,
            'categoryTypes' => $categoryTypes,
            'titlePage'     => 'Modification d\'évolution',
            'isUpdate'      => true,
        ]);
    }

    /**
     * Get unit evolution with note & comment
     *
     * @Route("/{technicalEvolution}", name="evolutionUnit")
     * @param TechnicalEvolution $technicalEvolution
     * @return Response
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function unitIndexAction(TechnicalEvolution $technicalEvolution)
    {
        if (($technicalEvolution->getStatus()->getValue() == 'En attente' && !$this->isGranted('ROLE_ADMIN'))
            || ($technicalEvolution->getUser() == !$this->getUser())) {
            return $this->redirectToRoute('evolutionHome');
        }

        $noteUser = '';
        $noteAnotherUser = '';

        $user    = $this->getUser();
        $userId  = $user->getId();
        $company = $user->getCompany();
        $teId    = $technicalEvolution->getId();

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User')->findBy([
            'company' => $company,
        ]);
        $teUnitRepository = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')->find($teId);
        $teRepository     = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $uteRepository    = $this->getDoctrine()->getRepository('AppBundle:UserTechnicalEvolution');

        $data  = $teRepository->getScoreForTechnicalEvolution($teId);
        $count = intval(($data[0])[2]); // total number of notes
        $total = intval(($data[0])[1]);//sum of notes
        $score = round(($data[0])[3], 1);//average of notes

        // Here we check if Technical Evolution is ON-GOING ("En cours"), , as only in that case voting will be possible
        $teStatusValue       = $teUnitRepository->getStatus()->getValue();

        if ($teStatusValue == 'En cours'){
            $teStatus = true;
        } else {
            $teStatus = false;
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or $this->isGranted('ROLE_SUPER_ADMIN')) {
            $validity = 3;
        }
        // Here we check if there is another Project Responsible in the company. Only one vote per company is possible and only by Project Responsible
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_PROJECT_RESP')) {
            $result = [];

            for ($i = 0; $i < count($userRepository); $i++) {
                $role = $userRepository[$i]->getRoles();
                if ($role[0] === "ROLE_PROJECT_RESP" && $userRepository[$i]->getId() != $userId) {
                    $result[] = $userRepository[$i]->getId();
                }
            }

            if (count($result)> 0) {
                $anotherUserId = $result[0];
            } else {
                $anotherUserId = null;
            }

            $dataUser = $teRepository->getNoteByUserPerTechnicalEvolution($teId, $userId);

            if ($anotherUserId === null) {
                if ($dataUser == []) {
                    // can be voted => add note in the database
                    $validity = 2;

                } else {
                    // Note can be modified ;
                    $noteUser = $dataUser[0]["note"];
                    $validity = 1;
                }
            } else {
                $dataAnotherUser = $teRepository->getNoteByUserPerTechnicalEvolution($teId, $anotherUserId);

                if ($dataAnotherUser == [] && $dataUser == []) {
                    // can be voted => add note in the database
                    $validity = 2;

                } elseif ($dataAnotherUser == [] && $dataUser != []) {
                    // Note can be modified ;
                    $noteUser = $dataUser[0]["note"];
                    $validity = 1;

                } else {
                    // It can not be voted as there are already votes from the same company;
                    $noteAnotherUser = $dataAnotherUser[0]["note"];
                    $validity = 0;
                }
            }
        } else {
            $validity = 4;
        }

        $notes          = $uteRepository->getUserTechnicalEvolution($teId, 'note', 999999999);
        $uteComment     = new UserTechnicalEvolution();
        $formComment    = $this->createForm(CommentUserTechnicalEvolutionType::class, $uteComment);
        $formUpdate     = $this->createForm(CommentUserTechnicalEvolutionType::class, null);
        $note           = new UserTechnicalEvolution('note');
        $formNote       = $this->createForm(NoteUserTechnicalEvolutionType::class, $note);

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution'       => $technicalEvolution,
            'notes'           => $notes,
            'addForm'         => $formComment->createView(),
            'updateForm'      => $formUpdate->createView(),
            'noteForm'        => $formNote->createView(),
            'validity'        => $validity,
            'teStatus'        => $teStatus,
            'noteAnotherUser' => $noteAnotherUser,
            'noteUser'        => $noteUser,
            'count'           => $count,
            'total'           => $total,
            'score'           => $score
        ]);
    }

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

        if ($form->isValid()) {
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
                'user'    => $userProfile->getFirstname() . ' ' . $userProfile->getLastname(),
                'date'    => $currentDate,
                'comment' => $comment->getComment()
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * Get user opened evolutions
     *
     * @Route("/utilisateur/liste", name="evolutionUser")
     * @return Response
     */
    public function userListAction()
    {
        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getSimpleEvolutions(['u.id' => $this->getUser()->getId()]);

        return $this->render('@App/Pages/Evolutions/waitingUserEvolution.html.twig', [
            'evolutions' => $evolutions,
        ]);
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
     * Update comments for TechnicalEvolutions
     * @Route("/commentaires/modification/{userTechnicalEvolution}", name="evolutionCommentsUpdate")
     * @param Request $request
     * @param UserTechnicalEvolution $userTechnicalEvolution
     * @return JsonResponse
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
     * Get full evolution have status
     *
     * @Route("/admin/en-attente/liste", name="evolutionWaiting")
     * @return Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminListWaitingAction()
    {
        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getSimpleEvolutions(['dtes.value' => 'En attente']);

        return $this->render('@App/Pages/Evolutions/waitingEvolution.html.twig', [
            'evolutions' => $evolutions,
        ]);
    }

    /**
     * Do action in evolution have status 'En attente'
     * Validate Or Closest action
     *
     * @Route("/en-attente/traitement/{technicalEvolution}", name="evolutionAdminWorks")
     * @param Request $request
     * @param TechnicalEvolution $technicalEvolution
     * @return JsonResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminWaitingWorksAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }

        $data = $request->request->get('data');

        if ($data == 'true')
            $newStatus = 'En cours';
        else if ($data == 'false')
            $newStatus = 'Refusé';
        else
            throw new Exception('Une erreur est survenue, veuillez réessayer plus tard');

        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository('AppBundle:Dictionary')->findOneBy([
            'type'  => 'evolution_status',
            'value' => $newStatus
        ]);

        $technicalEvolution->setStatus($status);
        $em->persist($technicalEvolution);
        $em->flush();

        /**
         * Mailling part (service)
         */
        $this->get('app.email.sending')->sendEmail(
            'Votre évolution vient de changer de status',
            $users = $this->get('app.getter_user_admin')->getAdmin(),
            $this->render('@App/Email/email.changeStatusEvolution.html.twig', [
                'url' => $this->generateUrl('evolutionUnit', [
                    'technicalEvolution' => $technicalEvolution->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'evolution' => $technicalEvolution
            ])
        );
        return new JsonResponse($data);
    }
}