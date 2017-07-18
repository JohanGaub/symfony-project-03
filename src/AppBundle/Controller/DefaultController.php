<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;

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
        $mentions = $this->getDoctrine()->getRepository('AppBundle:DynamicContent')
            ->findOneBy(['type' => 'mentionslegales']);

        return $this->render('@App/Pages/Index/index.html.twig', [
            'mentions' => $mentions
        ]);
    }

    /**
     * @Route("/historique", name="historique")
     */
    public function viewHistoriqueAction()
    {
        return $this->render('@App/Pages/Others/show-historique.html.twig');
    }

    /**
     * In this function just send links
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/telechargements", name="download")
     */
    public function DownloadIndexAction()
    {
        if($this->isGranted('ROLE_TECHNICIAN') || $this->isGranted('ROLE_COMMERCIAL')) {
            return $this->render('@App/Pages/Others/bo-download.html.twig', [
                'dirs' => $this->get('app.read_docfiles')->getDirContent()
            ]);
        } else {
            return $this->render('@App/Pages/Others/bo-dashboard.html.twig');
        }
    }

    /**
     * @param $type
     * @param $name
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|File|\Symfony\Component\HttpFoundation\Response
     * @Route("/telechargements/{type}/{name}", name="downloadFile")
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function DownloadAction($type, $name)
    {
        if($this->isGranted('ROLE_TECHNICIAN') || $this->isGranted('ROLE_COMMERCIAL')) {
            return $this->file($this->get('app.read_docfiles')->downloadFile($type, $name));
        } else {
            return $this->render('@App/Pages/Others/bo-dashboard.html.twig');
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tableau-de-bord", name="dashboard")
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function dashboardIndexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:News');

        return $this->render('@App/Pages/Others/bo-dashboard.html.twig', [
            'commercialNews'    => $repo->getNewsByType('Commerciale'),
            'technicalNews'     => $repo->getNewsByType('Technique'),
            'otherNews'         => $repo->getNewsByType('Autre')
        ]);
    }
}
