<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/{page}", name="evolutionHome")
     */
    public function indexAction($page = 1)
    {
        $params = [
        ];

        # get technical evolution repository
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');

        # Set Pagination parameters
        $evoByPage = 9;
        $test  = $repo->getNbEvolution($params);

        $pagination = [
            'page'          => $page,
            'route'         => 'evolutionHome',
            'pages_count'   => ceil(10 / $evoByPage),
            'route_params'  => array(),
        ];

        $evolutions = $repo->getListEvolution($page, $evoByPage, $params);

        return $this->render('AppBundle:Pages/Evolutions:index_evolution.html.twig', [
            'evolutions' => $evolutions,
            'pagination' => $pagination,
            'test'       => $test
        ]);
    }

}
