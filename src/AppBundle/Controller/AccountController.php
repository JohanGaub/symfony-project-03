<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 11/07/17
 * Time: 10:20
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class AccountController
 * @package AppBundle\Controller
 * @Route("/account", name="account")
 */
class AccountController extends Controller
{
    /**
     * @return mixed
     * @Route("/", name="account_result")
     * @param Request $request
     * @param User $user
     */
    public function indexAction()
    {

        $user = $this->getUser();

        $userProfile = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array('id' => $user));

        return $this->render('@App/Pages/Account/account.html.twig', array(
            'User' => $userProfile,
        ));
    }

    /**
     * Displays a form to edit an existing civil entity.
     *
     * @Route("/edit/{id}", name="account_modify")
     * @param Request $request
     * @param User $user
     * @return Response|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction(Request $request, User $user)
    {
        if ($this->isGranted('ROLE_PROJECT_RESP')) {
            $editForm = $this->createForm('AppBundle\Form\AccountModifyRespType', $user);
        } else {
            $editForm = $this->createForm('AppBundle\Form\AccountModifyUserType', $user);
        }
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('account_result', array('id' => $user->getId()));
        }

        return $this->render('@App/Pages/Account/modify_account.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }
}