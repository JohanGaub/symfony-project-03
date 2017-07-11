<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 11/07/17
 * Time: 10:20
 */

namespace AppBundle\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class AccountController
 * @package AppBundle\Controller
 * @Route("/account", name="account", )
 */
class AccountController extends Controller
{
    /**
     * @return mixed
     * @Route("/", name="account_result", )
     */
    public function indexAction() {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $userProfile = $em->getRepository('AppBundle:User')->findBy(array('id' => $user));

        return $this->render('@App/Pages/Account/account.html.twig', array(
            'User' => $userProfile,
        ));
    }
}