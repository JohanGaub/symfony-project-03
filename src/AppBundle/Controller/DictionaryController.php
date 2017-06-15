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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/liste", name="dictionaryHome")
     */
    public function indexAction()
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
        return $this->render('@App/Pages/Dictionary/indexDictionary.html.twig', [
            'dictionarys'   => $dictionarys
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
        $data       = $request->request->get('data');
        $fullType   = $data['type'];
        $type       = str_replace('dictionary_form_', '', $fullType);
        $value      = $data['value'];

        $dictionary = new Dictionary();
        $dictionary->setType($type);
        $dictionary->setValue($value);

        $em = $this->getDoctrine()->getManager();
        $em->persist($dictionary);
        $em->flush();

        $responseData = [
            'data'  => json_encode($value),
            "id"    => json_encode($dictionary->getId())
        ];

        return new JsonResponse($responseData);
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
        $newValue   = $getRequest->get('data');

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
