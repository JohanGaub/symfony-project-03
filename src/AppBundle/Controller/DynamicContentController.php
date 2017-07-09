<?php

namespace AppBundle\Controller;

use AppBundle\Form\DynamicContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DynamicContentController
 * @package AppBundle\Controller
 */
class DynamicContentController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/charte", name="charteIndex")
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function charteIndexAction()
    {
        $charte = $this->getDoctrine()->getRepository('AppBundle:DynamicContent')
            ->findOneBy(['type' => 'charte']);

        return $this->render('@App/Pages/DynamicContent/bo-dynamicContentIndex.html.twig', [
            'data' => $charte,
            'type'   => 'charte'
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/charte/modification", name="charteUpdate")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function charteUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $charte = $em->getRepository('AppBundle:DynamicContent')
            ->findOneBy(['type' => 'charte']);

        $form = $this->createForm(DynamicContentType::class, $charte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($charte);
            $em->flush();
            $this->addFlash('notice', 'Votre charte a bien été modifié');
            return $this->redirectToRoute('charteIndex');
        }

        return $this->render('@App/Pages/DynamicContent/bo-dynamicContentUpdate.html.twig', [
            'form' => $form->createView(),
            'type' => 'charte'
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/mentions-legales", name="mentionsIndex")
     * @Security("has_role('ROLE_FINAL_CLIENT')")
     */
    public function mentionsLegaleIndexAction()
    {
        $mentions = $this->getDoctrine()->getRepository('AppBundle:DynamicContent')
            ->findOneBy(['type' => 'mentionslegales']);

        return $this->render('@App/Pages/DynamicContent/bo-dynamicContentIndex.html.twig', [
            'data' => $mentions,
            'type' => 'mentions'
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/mentions-legales/modification", name="mentionsUpdate")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function mentionsLegaleUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mentions = $em->getRepository('AppBundle:DynamicContent')
            ->findOneBy(['type' => 'mentionslegales']);

        $form = $this->createForm(DynamicContentType::class, $mentions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mentions);
            $em->flush();
            $this->addFlash('notice', 'Vos mentions légales ont bien étés modifiés');
            return $this->redirectToRoute('mentionsIndex');
        }

        return $this->render('@App/Pages/DynamicContent/bo-dynamicContentUpdate.html.twig', [
            'form' => $form->createView(),
            'type' => 'mentions'
        ]);
    }

}
