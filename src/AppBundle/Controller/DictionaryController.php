<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dictionary;
use AppBundle\Form\Dictionary\DictionaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
            'form'          => $form->createView()
        ]);
    }

    /**
     * Add dictionary
     *
     * @param Request $request
     * @return JsonResponse|Response
     * @Route("/nouveau", name="dictionaryAdd")
     */
    public function addAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }

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

        $id = $dictionary->getId();

        return new JsonResponse([
            "data" => json_encode($data),
            "id" => json_encode($id)
        ]);
    }

    /**
     * Update dicionary
     *
     * @Route("/modification/{dictionaryId}", name="dictionaryUpdate")
     * @param Request $request
     * @param $dictionaryId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $dictionaryId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }

        $getRequest = $request->request;
        $newValue = $getRequest->get('data');

        $dictionary = $this->getDoctrine()->getRepository('AppBundle:Dictionary')
            ->find($dictionaryId);
        $dictionary->setValue($newValue);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dictionary);
        $em->flush();

        return new JsonResponse('Valid XmlHttp request !');
    }

    /**
     * @param Request $request
     * @param $dictionaryId
     * @return JsonResponse
     * @Route("/suppression/{dictionaryId}", name="dictionaryDelete")
     */
    public function deleteAction(Request $request, $dictionaryId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException('500', 'Invalid call');
        }

        $dictionary = $this->getDoctrine()->getRepository('AppBundle:Dictionary')
            ->find($dictionaryId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($dictionary);
        $em->flush();

        return new JsonResponse('Valid XmlHttp request !');
    }
}
