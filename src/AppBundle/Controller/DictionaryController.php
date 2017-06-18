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
        $repo           = $this->getDoctrine()->getRepository('AppBundle:Dictionary');
        $dictionaryList = $repo->getDictionaryList();
        $dictionarys    = [];

        foreach ($dictionaryList as $dictionary) {
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
     * @Route("/nouveau", name="dictionaryAdd")
     * @param Request $request
     * @return JsonResponse|Response
     * @Security("has_role('ROLE_ADMIN')")
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
        $fullType   = htmlspecialchars($data['type']);
        $type       = str_replace('dictionary_form_', '', $fullType);
        $value      = htmlspecialchars($data['value']);

        $dictionary = new Dictionary();
        $dictionary->setType($type);
        $dictionary->setValue($value);

        $em = $this->getDoctrine()->getManager();
        $em->persist($dictionary);
        $em->flush();

        $responseData = [
            'data'  => $value,
            "id"    => $dictionary->getId()
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
     * @Security("has_role('ROLE_ADMIN')")
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
     * Delete dictionary
     *
     * @Route("/suppression/{dictionaryId}", name="dictionaryDelete")
     * @param Request $request
     * @param $dictionaryId
     * @return JsonResponse|Response
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
            return new Response('error_datas_001');
        }

        return new JsonResponse('Valid XmlHttp request !');
    }
}
