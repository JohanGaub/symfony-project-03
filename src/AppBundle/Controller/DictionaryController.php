<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dictionary;
use AppBundle\Entity\UserTechnicalEvolution;
use AppBundle\Form\Dictionary\DictionaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DictionaryController
 * @package AppBundle\Controller
 * @Route("/dictionnaire", name="dictionaryArea")
 */
class DictionaryController extends Controller
{
    /**
     * Index dictionary
     *
     * @Route("/liste", name="dictionaryHome")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Dictionary');

        $typeTitle      = 'category_type';
        $statusTitle    = 'technical_evolution_status';
        $originTitle    = 'technical_evolution_origin';

        $typeList   = $repo->getItemListByType($typeTitle);
        $statusList = $repo->getItemListByType($statusTitle);
        $originList = $repo->getItemListByType($originTitle);

        $dictionary = new Dictionary();
        $typeForm = $this->createForm(DictionaryType::class, $dictionary);
        $statusForm = $this->createForm(DictionaryType::class, $dictionary);
        $originForm = $this->createForm(DictionaryType::class, $dictionary);
        $generalUpdateForm = $this->createForm(DictionaryType::class, $dictionary);

        return $this->render('@App/Pages/Dictionary/indexDictionary.html.twig', [
            /** Here get my title "type" */
            'typeTitle'     => $typeTitle,
            'statusTitle'   => $statusTitle,
            'originTitle'   => $originTitle,
            /** Here get my dictionary's data */
            'typeList'      => $typeList,
            'statusList'    => $statusList,
            'originList'     => $originList,
            /** Here get my form */
            'typeForm'      => $typeForm->createView(),
            'statusForm'    => $statusForm->createView(),
            'originForm'    => $originForm->createView(),
            'genUpdateForm' => $generalUpdateForm->createView()
        ]);
    }

    /**
     * Add dictionary
     *
     * @Route("/nouveau/{type}", name="dictionaryAdd")
     * @param Request $request
     * @param string $type
     * @return JsonResponse|Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request, string $type)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        /**
         * Get result from ajax call
         * delete 'dictionary_form_' from id to get category type
         */
        $dictionary = new Dictionary();
        $form = $this->createForm(DictionaryType::class, $dictionary);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $verification = $em->getRepository('AppBundle:Dictionary')
            ->findBy(['type' => $type, 'value' => $dictionary->getValue()]);

        if (count($verification) > 0) {
            $data = [
                'status'    => 'error',
                'element'   => 'Vous avez déjà une entrée qui porte ce nom !'
            ];
            return new JsonResponse($data);
        }
        if ($form->isValid()) {
            $dictionary->setType($type);
            $em->persist($dictionary);
            $em->flush();

            $data = [
                'status'    => 'succes',
                'element'   => $dictionary->getId()
            ];
            return new JsonResponse($data);
        }

    }

    /**
     * Update dicionary
     *
     * @Route("/modification/{dictionaryId}", name="dictionaryUpdate")
     * @param Request $request
     * @param $dictionaryId
     * @return JsonResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $dictionaryId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Dictionary');

        $dictionary = $repo->find($dictionaryId);
        $form = $this->createForm(DictionaryType::class, $dictionary);
        $form->handleRequest($request);

        $verification = $repo->findBy(['value' => $dictionary->getValue()]);

        if (count($verification) > 0) {
            $data = [
                'status'    => 'error',
                'element'   => 'Il y a déjà une entée qui a ce nom !'
            ];
            return new JsonResponse($data);
        }

        $dictionary = $repo->find($dictionaryId);
        $form = $this->createForm(DictionaryType::class, $dictionary);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($dictionary);
            $em->flush();

            $data = [
                'status' => 'succes'
            ];
            return new JsonResponse($data);
        }
    }

    /**
     * Delete dictionary
     *
     * @Route("/suppression/{dictionaryId}", name="dictionaryDelete")
     * @param Request $request
     * @param $dictionaryId
     * @return JsonResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $dictionaryId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }
        try {
            $dictionary = $this->getDoctrine()->getRepository('AppBundle:Dictionary')
                ->find($dictionaryId);
            $em = $this->getDoctrine()->getManager();
            $em->remove($dictionary);
            $em->flush();
        } catch (\Exception $e) {
            $data = [
                'status'    => 'error',
                'element'   => 'Vous ne pouvez pas supprimer cette entrée, des élements y sonts associés !'
            ];
            return new JsonResponse($data);
        }
        return new JsonResponse(['status' => 'succes']);
    }
}
