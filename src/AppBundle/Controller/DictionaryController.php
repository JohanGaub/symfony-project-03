<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dictionary;
use AppBundle\Form\Dictionary\DictionaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/liste", name="dictionaryHome")
     */
    public function indexAction(Request $request)
    {
        $repo           = $this->getDoctrine()->getRepository('AppBundle:Dictionary');
        $dictionaryList = $repo->getDictionaryList();
        $dictionarys    = [];

        foreach ($dictionaryList as $dictionary)
        {
            $type = $dictionary->getType();
            if (array_key_exists($type, $dictionarys)) {
                $dictionarys[$type][] = $dictionary;
            } else {
                $dictionarys[$type] = [$dictionary];
            }
        }

        $dictionaryNewObj = new Dictionary();
        $form = $this->createForm(DictionaryType::class, $dictionaryNewObj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dictionaryNewObj);
            $em->flush();
        }

        return $this->render('@App/Pages/Dictionary/index_dictionary.html.twig', [
            'dictionarys'   => $dictionarys,
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @Route("/nouveau", name="dictionaryAdd")
     */
    public function addAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        } else {
            /**
             * Get result from ajax call
             * delete 'dictionary_form_' from id to get category type
             */
            $getRequest = $request->request;
            $fullType = $getRequest->get('dataType');
            $type = str_replace('dictionary_form_', '', $fullType);
            $data = $getRequest->get('dataForm');

            $dictionary = new Dictionary();
            $dictionary->setType($type);
            $dictionary->setValue($data);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dictionary);
            $em->flush();

            return new JsonResponse(array("data" => json_encode($data)));
        }
    }

    /**
     * @param $dictionaryId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/suppression/{dictionaryId}", name="dictionaryDelete")
     */
    public function deleteAction($dictionaryId)
    {
        $doctrine = $this->getDoctrine();
        $dictionary = $doctrine->getRepository('AppBundle:Dictionary')
            ->find($dictionaryId);
        $em = $doctrine->getManager();
        $em->remove($dictionary);
        $em->flush();
        $this->addFlash("notice", "L'entrée du dictionnaire a bien été supprimé !");
        return $this->redirectToRoute('dictionaryHome');
    }
}
