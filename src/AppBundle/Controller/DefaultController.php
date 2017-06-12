<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }

    /**
     * @Route("/historique", name="historique")
     */
    public function viewHistoriqueAction()
    {
        return $this->render('AppBundle:Pages:historique.html.twig');
    }

    /**
     * This function is temporary to check if default path after login works well to be deleted and placed somewhere else
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('AppBundle:Partials:dashboard.html.twig');
    }

}
