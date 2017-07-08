<?php

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Form\News\NewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class NewsController
 * @package AppBundle\Controller
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * @Route("/", name="newsHome")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $news = $this->getDoctrine()->getRepository('AppBundle:News')
            ->findAll();

        return $this->render('@App/Pages/News/indexNews.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * @Route("/nouvelle", name="newsAdd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $new = new News();
        $form = $this->createForm(NewsType::class, $new);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $new->setType($form['type']->getData());
            $new->setCreationDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($new);
            $em->flush();
            $this->addFlash('notice', 'Votre news à bien été enregistré');
            return $this->redirectToRoute('newsUnit', ['new' => $new->getId()]);
        }

        return $this->render('@App/Pages/News/basicFormNews.html.twig', [
            'titlePage' => 'Nouvelle News',
            'form'      => $form->createView()
        ]);
    }

    /**
     * @param News $new
     * @Route("/admin/{new}", name="newsUnit")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unitAction(News $new)
    {
        return $this->render('@App/Pages/News/unitNews.html.twig', [
            'new' => $new
        ]);
    }

    /**
     * @param Request $request
     * @param News $new
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/modification/{new}", name="newsUpdate")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, News $new)
    {
        $form = $this->createForm(NewsType::class, $new);
        $form->handleRequest($request);

        $em     = $this->getDoctrine()->getManager();
        $types  = $em->getRepository('AppBundle:Dictionary')->getItemListByTypeResult('category_type');

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($new);
            $em->flush();
            $this->addFlash('notice', 'Votre news a bien été modifié !');
            return $this->redirectToRoute('newsUnit', ['new' => $new->getId()]);
        }

        return $this->render('@App/Pages/News/basicFormNews.html.twig', [
            'titlePage' => 'Modification du nouvelle',
            'form'      => $form->createView(),
            'isUpdate'  => true,
            'types'     => $types,
            'type'      => $new->getType()->getId()
        ]);
    }

    /**
     * @param News $new
     * @Route("/suppression/{new}", name="newsDelete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(News $new)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($new);
        $em->flush();
        $this->addFlash('notice', 'Votre news a bien été supprimé !');
        return $this->redirectToRoute('newsHome');
    }

    /**
     * @param News $new
     * @Route("/lire/{new}", name="newsUserUnit")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function unitUserAction(News $new)
    {
        return $this->render('@App/Pages/News/unitUserNews.html.twig', [
            'new' => $new
        ]);
    }
}