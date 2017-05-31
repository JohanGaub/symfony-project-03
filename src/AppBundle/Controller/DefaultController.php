<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Finder\Finder;


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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/test", name="test")
     */
    public function test()
    {
        $finder = new Finder();
        $finder->files()->in('../web/assets/img');

        foreach ($finder as $file) {
            // Dump the absolute path
            $test1 = ($file->getRealPath());

            // Dump the relative path to the file, omitting the filename
            $test2 = ($file->getRelativePath());

            // Dump the relative path to the file
            $test3 = ($file->getRelativePathname());


        }
        return $this->render('@App/Partials/test.html.twig', [
            'test1' => $test1,
            'test2' => $test2,
            'test3' => $test3,
        ]);

    }
}
