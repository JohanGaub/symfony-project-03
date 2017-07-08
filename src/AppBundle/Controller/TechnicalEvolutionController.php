<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
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
    const MAX_BY_PAGE = 8;
    /**
     * List all evolution with filter
     *
     * @Route("/liste/{page}", name="evolutionHome")
     * @param int $page
     * @return Response
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function indexAction(int $page = 1)
    {
        // TODO => Don't forget to change that 0 = 0
        $repo       = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $evoTotal   = $repo->getNbEvolution('0 = 0');
        $pagination = [
            'page'          => $page,
            'route'         => 'evolutionHome',
            'pages_count'   => ceil($evoTotal / self::MAX_BY_PAGE),
            'route_params'  => array(),
        ];
        $evolutions = $repo->getEvolutions('0 = 0', $page, self::MAX_BY_PAGE);

        // TODO => Find a better solution for rounding (implement ROUND to DQL)
        foreach ($evolutions as $key => $value) {
            if (strlen($value['avg_notes']) > 3) {
                $evolutions[$key]['avg_notes'] = substr($value['avg_notes'], 0, 3);
            }
        }
        return $this->render('AppBundle:Pages/Evolutions:indexEvolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Add new evolution
     *
     * @Route("/nouveau", name="evolutionAdd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $te = new TechnicalEvolution();
        $form = $this->createForm(TechnicalEvolutionType::class, $te);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $dictionaryStatus = $em->getRepository('AppBundle:Dictionary')
                ->findOneBy(['type' => 'status', 'value' => 'En attente']);

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

        $categorys = $em->getRepository('AppBundle:Category')
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
            return $this->redirectToRoute('evolutionUnit', [
                'technicalEvolution' => $technicalEvolution->getId()
            ]);
        }
        return $this->render($view, [
            'form'          => $form->createView(),
            'categoryId'    => isset($category) ? $category->getId() : null,
            'categoryType'  => isset($categoryType) ? $categoryType->getId() : null,
            'categorys'     => $categorys,
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
        $uteComment     = new UserTechnicalEvolution();
        $formComment    = $this->createForm(CommentUserTechnicalEvolutionType::class, $uteComment);
        $formUpdate     = $this->createForm(CommentUserTechnicalEvolutionType::class, null);

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution' => $technicalEvolution,
            'addForm'   => $formComment->createView(),
            'updateForm'=> $formUpdate->createView()
        ]);
    }

    /**
     * Get user opened evolutions
     *
     * @Route("/utilisateur/liste", name="evolutionUser")
     * @return Response
     * @Security("has_role('ROLE_PROJECT_RESP')")
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
        $data   = $request->request->get('data');
        if ($data == 'true') {
            $newStatus = 'En cours';
        } else if ($data == 'false') {
            $newStatus = 'Refusé';
        } else {
            throw new Exception('Une erreur est survenue, veuillez réessayer plus tard');
        }
        $em     = $this->getDoctrine()->getManager();
        $status = $em->getRepository('AppBundle:Dictionary')->findOneBy([
            'type'  => 'status',
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