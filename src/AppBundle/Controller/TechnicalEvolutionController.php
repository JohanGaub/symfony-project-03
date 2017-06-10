<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Form\Evolution\TechnicalEvolutionType;
use AppBundle\Form\Evolution\UpdateAdminTechnicalEvolutionType;
use AppBundle\Form\Evolution\UpdateTechnicalEvolutionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TechnicalEvolutionController
 * @package AppBundle\Controller
 * @Route("/evolution-technique", name="evolutionArea")
 */
class TechnicalEvolutionController extends Controller
{
    /**
     * @Route("/liste/{page}", name="evolutionHome")
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $params = [
            'ct.value' => 'technical',
        ];

        # get technical evolution repository
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');

        # Set Pagination parameters
        $evoByPage = 9;
        $evoTotal  = count($repo->getNbEvolution($params));

        $pagination = [
            'page'          => $page,
            'route'         => 'evolutionHome',
            'pages_count'   => ceil($evoTotal / $evoByPage),
            'route_params'  => array(),
        ];

        $evolutions = $repo->getListEvolution($params, $page, $evoByPage);

        return $this->render('AppBundle:Pages/Evolutions:indexEvolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination
        ]);
    }

    /**
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
             * TODO -> need to rework on user relations
             * $user = $this->getUser();
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
            $form = $this->createForm(UpdateTechnicalEvolutionType::class, $te);
            $view = '@App/Pages/Evolutions/basicFormEvolution.html.twig';
        } else {
            $form = $this->createForm(UpdateAdminTechnicalEvolutionType::class, $te);
            $view = '@App/Pages/Evolutions/adminFormEvolution.html.twig';
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $te->setUpdateDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($te);
            $em->flush();

            $this->redirectToRoute('evolutionUnit', ['technicalEvolutionId' => $technicalEvolutionId]);
        }

        return $this->render($view, ['form' => $form->createView()]);
    }

    /**
     * @Route("/{technicalEvolutionId}", name="evolutionUnit")
     * @param int $technicalEvolutionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unitIndexAction(int $technicalEvolutionId)
    {
        $evolution = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution')
            ->getUnitEvolution($technicalEvolutionId)[0];

        return $this->render('@App/Pages/Evolutions/unitIndexEvolution.html.twig', [
            'evolution' => $evolution
        ]);
    }

    /**
     * @Route("/admin/liste", name="evolutionWaiting")
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

}
