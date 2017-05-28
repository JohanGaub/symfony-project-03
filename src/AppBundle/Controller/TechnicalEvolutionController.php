<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Form\TechnicalEvolutionType;
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
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/liste/{page}", name="evolutionHome")
     */
    public function indexAction($page = 1)
    {
        $params = [
            'c.type' => 'other'
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

        $evolutions = $repo->getListEvolution($page, $evoByPage, $params);

        return $this->render('AppBundle:Pages/Evolutions:index_evolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/nouveau", name="evolutionAdd")
     */
    public function addAction(Request $request)
    {
        $te = new TechnicalEvolution();
        $form = $this->createForm(TechnicalEvolutionType::class, $te);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($te);
            $em->flush();
            $this->addFlash('notice', 'Votre demande d\'évolution à bien été prise en compte !');
            return $this->redirectToRoute('evolutionHome');
        }

        return $this->render('AppBundle:Pages/Evolutions:add_evolution.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
