<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\Category\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/categorie", name="categoryArea")
 */
class CategoryController extends Controller
{
    /**
     * Index all categories
     *
     * @Route("/liste", name="categoryHome")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->getCategories();
        return $this->render('@App/Pages/Category/indexCategory.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Add new category
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/nouvelle", name="categoryAdd")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form =$this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('notice', 'Votre nouvelle catégorie a été ajouté à la liste.');
            return $this->redirectToRoute('categoryHome');
        }

        return $this->render('@App/Pages/Category/formBasicCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Update one category
     *
     * @Route("/modification/{category}", name="categoryUpdate")
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('notice', 'Votre modification a bien été prise en compte !');
            return $this->redirectToRoute('categoryHome');
        }

        return $this->render('@App/Pages/Category/formBasicCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete one category
     *
     * @param Category $category
     * @Route("/suppression/{category}", name="categoryDelete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Category $category)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('notice', 'Votre catégorie a bien été supprimé !');
        } catch(\Exception $exception) {
            $this->addFlash('notice', 'Vous ne pouvez pas supprimer cette catégorie tant que des éléments y sont attachés !');
        }

        return $this->redirectToRoute('categoryHome');
    }
}
