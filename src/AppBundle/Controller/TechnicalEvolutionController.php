<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Form\Evolution\NoteUserTechnicalEvolutionType;
use AppBundle\Form\Evolution\SearchTechnicalEvolution;
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
     * @Route("/liste/{page}", name="evolutionHome")
     * @param Request $request
     * @param int $page
     * @return Response
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function indexAction(Request $request, int $page = 1)
    {
        /**
         * TODO => Transform that array in searchFormEngine ??
         * TODO => Maybe get and this format with general params ? evolution-technique/liste/1/te.id=000&te.title=name
         * TODO => Find a solution to block access to any status for user update
         * TODO => Access technical / commercial ?
         */
        $searchForm = $this->createForm(SearchTechnicalEvolution::class);
        $searchForm->handleRequest($request);

        $searchTitle = '';
        $searchStatus = '';

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTitle    = $searchForm['search']->getData();
            $searchStatus   = $searchForm['status']->getData();
        }
        $params = [
            'te.title'      => !empty($searchTitle) ? $searchTitle : '',
            'dtes.value'    => !empty($searchStatus) ? $searchStatus : 'En cours',
        ];

        $paramsTranformers = $this->get('app.sql.search_params_getter');
        $allowParamsFormat = $paramsTranformers->setParams($params)->getParams();

        // get technical evolution repository
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        // set Pagination parameters
        $evoByPage = 8;
        $evoTotal = $repo->getNbEvolution($allowParamsFormat);

        $pagination = [
            'page'          => $page,
            'route'         => 'evolutionHome',
            'pages_count'   => ceil($evoTotal / $evoByPage),
            'route_params'  => array(),
        ];
        $evolutions = $repo->getEvolutions($allowParamsFormat, $page, $evoByPage);

        // TODO => Find a better solution for rounding (implement ROUND to DQL)
        foreach ($evolutions as $key => $value) {
            if (strlen($value['avg_notes']) > 3) {
                $evolutions[$key]['avg_notes'] = substr($value['avg_notes'], 0, 3);
            }
        }

        return $this->render('AppBundle:Pages/Evolutions:indexEvolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * Add new evolution
     * TODO -> mail & admin confirmation (service)
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

        if ($form->isSubmitted() && $form->isValid()){
            $dictionaryStatus = $this->getDoctrine()->getRepository('AppBundle:Dictionary')
                ->findOneBy(['type' => 'technical_evolution_status', 'value' => 'En attente']);

            $te->setCreationDate(new \DateTime('now'));
            $te->setStatus($dictionaryStatus);
            $te->setUser($this->getUser());
            $te->setCategory($form->getData()->getCategory());

            $em = $this->getDoctrine()->getManager();
            $em->persist($te);
            $em->flush();
            $this->addFlash('notice', 'Votre demande d\'évolution à bien été prise en compte !');
            return $this->redirectToRoute('evolutionUser');
        }
        return $this->render('@App/Pages/Evolutions/basicFormEvolution.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Update evolution (Admin && Basic users)
     * TODO => Fix preselect category & category_type
     *
     * @Route("/modification/{technicalEvolution}", name="evolutionUpdate")
     * @param Request $request
     * @param TechnicalEvolution $technicalEvolution
     * @return Response
     */
    public function updateAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        $em = $this->getDoctrine()->getManager();
        $te = $em->getRepository('AppBundle:TechnicalEvolution')
            ->find($technicalEvolution);

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
                'technicalEvolution' => $technicalEvolution
            ]);
        }
        return $this->render($view, ['form' => $form->createView()]);
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
        $uteRepository  = $this->getDoctrine()->getRepository('AppBundle:UserTechnicalEvolution');
        $teId           = $technicalEvolution->getId();
        $comments       = $uteRepository->getUserTechnicalEvolution($teId, 'comment', 10);
        $notes          = $uteRepository->getUserTechnicalEvolution($teId, 'note', 999999999);
        $uteComment     = new UserTechnicalEvolution();
        $formComment    = $this->createForm(CommentUserTechnicalEvolutionType::class, $uteComment);
        $formUpdate     = $this->createForm(CommentUserTechnicalEvolutionType::class, null);

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution' => $technicalEvolution,
            'comments'  => $comments,
            'notes'     => $notes,
            'addForm'   => $formComment->createView(),
            'updateForm'=> $formUpdate->createView(),
        ]);
    }

    /**
     * Get user opened evolutions
     *
     * @Route("/utilisateur/liste", name="evolutionUser")
     * @return Response
     */
    public function userListAction()
    {
        $params = ['u.id' => $this->getUser()->getId()];
        $paramsTranformers = $this->get('app.sql.search_params_getter');
        $allowParamsFormat = $paramsTranformers->setParams($params)->getParams();

        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getSimpleEvolutions($allowParamsFormat);

        return $this->render('@App/Pages/Evolutions/waitingUserEvolution.html.twig', [
            'evolutions' => $evolutions,
        ]);
    }

    /**
     * Get full evolution have status
     *
     * @Route("/admin/en-attente/liste", name="evolutionWaiting")
     * @return Response
     */
    public function adminListWaitingAction()
    {
        $params = [
            'dtes.value' => 'En attente'
        ];
        $paramsTranformers = $this->get('app.sql.search_params_getter');
        $allowParamsFormat = $paramsTranformers->setParams($params)->getParams();

        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getSimpleEvolutions($allowParamsFormat);

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
     */
    public function adminWaitingWorksAction(Request $request, TechnicalEvolution $technicalEvolution)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $data   = $request->request->get('data');

        if ($data === 'true') {
            $newStatus = 'En cours';
        } else if ($data === 'false') {
            $newStatus = 'Refusé';
        } else {
            throw new Exception('Une erreur est survenue, veuillez réessayer plus tard');
        }
        $em     = $this->getDoctrine()->getManager();
        $status = $em->getRepository('AppBundle:Dictionary')->findOneBy([
            'type'  => 'technical_evolution_status',
            'value' => $newStatus
        ]);

        $technicalEvolution->setStatus($status);
        $em->persist($technicalEvolution);
        $em->flush();

        return new JsonResponse($data);
    }

}
