<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class TestController extends Controller
{
    /**
     * Calculation of Technical Evolution Score
     *
     * @internal  param $name
     * @return Response
     * @Route("/score",  name="score")
     */
    public function scoreAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $result = $repo->getScoreForTechnicalEvolution(12);

        $count = intval(($result[0])[2]); // total number of notes
        $total = intval(($result[0])[1]);//sum of the notes
        $score = round(($result[0])[3], 1);//average of notes

        dump($result);
        dump($count);
        dump($total);
        dump($score);

        return new Response(
            '<html><body>Score of the Technical Evolution </body></html>'
        );

    }

    /**
     *
     * @internal  param $name
     * @return Response
     * @Route("/note",  name="note")
     */
    public function noteAction()

    {

        $user = $this->getUser();
        $userId = $user->getId();
        $company = $user->getCompany();
        $repo = $this->getDoctrine()->getRepository('AppBundle:User')->findBy([
            'company' => $company,
        ]);

        //$count = count($repo);
        $result = [];

        for ($i = 0; $i < count($repo); $i++) {
            $role = $repo[$i]->getRoles();
            if ($role[0] === "ROLE_PROJECT_RESP" && $repo[$i]->getId() != $userId) {
                $result[] = $repo[$i]->getId();
            }
        }

        if (count($result)> 0) {
            $anotherUserId = $result[0];
        } else {
            $anotherUserId = null;
        }
        dump($userId);
        dump($anotherUserId);

        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $dataUser = $repo->getNoteByUserPerTechnicalEvolution(12, $userId);

        if ($anotherUserId === null) {
            if ($dataUser == []) {

                return new Response('you can vote now');// you can vote now ; there are no constraints  !!!! pay attention on null results

            } else {
                //you can modify your note ; your previous note was :  value/10
                $noteUser = $dataUser[0]["note"];
                dump($noteUser);

                return new Response('You can modify your note. Previous one was: ' . $noteUser . ' / 10');

            }
        } else {
            $dataAnotherUser = $repo->getNoteByUserPerTechnicalEvolution(12, $anotherUserId);
            if ($dataAnotherUser == [] && $dataUser == []) {
                // you can vote now; here all the part for creation

                return new Response('you can vote now');

            } elseif ($dataAnotherUser == [] && $dataUser != []) {
                //you can modify your note ; your previous note was :  value/10

                $noteUser = $dataUser[0]["note"];
                dump($noteUser);

                return new Response('you can modify your note. Your previous note was: ' . $noteUser .' / 10 ');

            } else {
                // sorry, but there are already votes from your company;  value/10
                $noteAnotherUser = $dataAnotherUser[0]["note"];
                dump($noteAnotherUser);

                return new Response('Sorry, but there is already note from your company. It is: ' . $noteAnotherUser .' / 10 ');
            }

        }
    }


    /**
     * Get
     *
     * @internal  param $name
     * @return Response
     * @Route("/test",  name="test")
     */
    public function testAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $data = $repo->getNoteByUserPerTechnicalEvolution(12, 8);
// $userId = 9
// $anotherUserId = 11
        $count = count($data);
        $result = [];
        $note = null;
        for ($i = 0; $i < count($data); $i++) {
            $result[] = ($data[$i])["note"];
        }
        for ($i = 0; $i < count($result); $i++){
            if ($result[$i] !== null) {
                $note = $result[$i];
            }
        }

        dump($data);
        dump($count);
        dump($result);
        dump($note);

        return new Response(
            '<html><body>Users</body></html>'
        );
    }

    /**
     * Get
     *
     * @internal  param $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/count",  name="count")
     */
    public function countAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:UserTechnicalEvolution');
        $data = $repo->countNotesByCompany(12, 7);

        dump($data);

        return new Response('Count notes by company');
    }


    /**
     * Get
     *
     * @internal  param $name
     * @return Response
     * @Route("/list",  name="list")
     */
    public function listAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:TechnicalEvolution');
        $notes = $repo->getUserEvaluationsByTechnicalEvolution(51);
        $category = $repo->getCategoryByTechnicalEvolution(51);

        $category_title = $category[0]['title'];
        $category_type = $category[0]['value'];

        dump($notes);
        dump($category);
        dump($category_title);
        dump($category_type);

        return new Response('Count notes by company');

    }


    /**
     * @internal  param $name
     * @return Response
     * @Route("/status", name="status")
     *
     */
    public function categoryAction()
    {
        $user           = $this->getUser();
        $em             = $this->getDoctrine()->getManager();
        $teRepository         = $em->getRepository('AppBundle:TechnicalEvolution')->find(10);
        $technicalEvolutionStatus = $teRepository->getStatus()->getId();
        // Here we check if Technical Evolution is on-going, via id of status
        if ($technicalEvolutionStatus == 5){
            $status = true;
        } else {
            $status = false;
        }

        dump($user);
        dump($technicalEvolutionStatus);
        dump($status);

        return new Response('Status of Technical evolution is: ' . $technicalEvolutionStatus );
    }
}
