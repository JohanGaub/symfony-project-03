<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
        return $this->render('@App/Pages/Index/index.html.twig');
    }

    /**
     * @Route("/historique", name="historique")
     */
    public function viewHistoriqueAction()
    {
        return $this->render('@App/Pages/Others/show-historique.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/telechargements", name="download")
     */
    public function DownloadIndexAction()
    {
        return $this->render('@App/Pages/Others/bo-download.html.twig', [
            'dirs' => $this->get('app.read_docfiles')->getDirContent()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tableau-de-bord", name="dashboard")
     */
    public function dashboardIndexAction()
    {
        return $this->render('@App/Pages/Others/bo-dashboard.html.twig', [

        ]);
    }

}
