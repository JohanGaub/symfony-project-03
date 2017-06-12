<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Form\Evolution\AdminTechnicalEvolutionType;
use AppBundle\Form\Evolution\TechnicalEvolutionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(int $page = 1)
    {
        $params = [
            'ct.value' => 'commercial',
        ];

        $paramsTranformers = $this->get('app.sql.search_params_getter');
        $allowParamsFormat = $paramsTranformers->setParams($params)->getParams();

        # get technical evolution repository
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');

        # Set Pagination parameters
        $evoByPage = 9;
        $evoTotal  = count($repo->getNbEvolution($allowParamsFormat));

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $te = new TechnicalEvolution();
        $form = $this->createForm(TechnicalEvolutionType::class, $te);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * Form works
             */
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, int $technicalEvolutionId)
    {
        $te = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
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
            $em = $this->getDoctrine()->getManager();
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
     * @param int $technicalEvolutionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unitIndexAction(int $technicalEvolutionId)
    {
        $doctrine = $this->getDoctrine();

        $evolution = $doctrine->getRepository('AppBundle:TechnicalEvolution')
            ->getUnitEvolution($technicalEvolutionId)[0];
        $uteRepository = $doctrine->getRepository('AppBundle:UserTechnicalEvolution');

        $comments   = $uteRepository->getUserTechnicalEvolution($evolution['te_id'], 'comment');
        $notes      = $uteRepository->getUserTechnicalEvolution($evolution['te_id'], 'note');

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution' => $evolution,
            'comments'  => $comments,
            'notes'     => $notes
        ]);
    }

    /**
     * Get full evolution have status
     *
     * @Route("/en-attente/liste", name="evolutionWaiting")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminListWaitingAction()
    {
        $evolutions = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getListWaitingEvolution();

        return $this->render('@App/Pages/Evolutions/waitingEvolution.html.twig', [
            'evolutions' => $evolutions
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
        $data       = $request->request->get('data');
        $doctrine   = $this->getDoctrine();

        if ($data === true) {
            // if validate action
            $dictionary = $doctrine->getRepository('AppBundle:Dictionary')
                ->findBy(array('value' => 'En cours'))[0];
        } else {
            // else to bad for validate
            $dictionary = $doctrine->getRepository('AppBundle:Dictionary')
                ->findBy(array('value' => 'Refusé'))[0];
        }

        $evolution  = $doctrine->getRepository('AppBundle:TechnicalEvolution')
            ->find($technicalEvolutionId);
        $evolution->setStatus($dictionary);

        $em = $doctrine->getManager();
        $em->persist($evolution);
        $em->flush();

        return new JsonResponse('Valid XmlHttp request !');
    }

}
