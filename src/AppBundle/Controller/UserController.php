<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Form\UserAssociateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/associate_validation", name="associate")
 *
 */
class UserController extends controller
{
    /**
     * @Route("/register_associate", name="add_associate")
     * @param Request $request
     * @return Response|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param UserPasswordEncoderInterface $passwordEncoder
     * @Security("has_role ('ROLE_PROJECT_RESP')")
     */
    public function RegisterAssociateAction(Request $request)
    {
        $user = new User();
        $userProfile = new UserProfile();

        $form = $this->createForm(UserAssociateType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setIsActive(false);
            $user->setIsActiveByAdmin(true);
            $user->setToken($user->generateToken());
            $dueDate = new \DateTime("now");
            $dueDate->add(new  \DateInterval("P1D"));
            $user->setTokenLimitDate($dueDate);
            $company = $this->getUser()->getCompany();
            $user->setCompany($company);

            $userProfile->setFirstname($form['userProfile']['firstname']->getData());
            $userProfile->setLastname($form['userProfile']['lastname']->getData());
            $userProfile->setPhone($form['userProfile']['phone']->getData());

            $profile = $this->getUser();
            $userId = $profile->getId();
            $company = $profile->getCompany();
            $repo = $this->getDoctrine()->getRepository('AppBundle:User')->findBy([
                'company' => $company,
            ]);
            $result = [];

            for ($i = 0; $i < count($repo); $i++) {
                $role = $repo[$i]->getRoles();
                if ($role[0] === "ROLE_PROJECT_RESP" && $repo[$i]->getId() != $userId) {
                    $result[] = $repo[$i]->getId();
                }
            }

            $capacity = $user->getRoles();
            if(count($result) == 1 && ($capacity[0] === "ROLE_PROJECT_RESP")){
                $this->addFlash("notice", "Validation impossible. Vous pouvez créer deux comptes responsable projet par société.");
                return $this->redirectToRoute('add_associate');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mailer = \Swift_Mailer::newInstance($this->get('mailer')->getTransport());

            $email = \Swift_Message::newInstance()
                ->setSubject('CommunIt : Confirmation de inscription')
                ->setFrom($this->getParameter('mailer_sender_address'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('@App/Email/confirm.html.twig', [
                        'name' => $user->getUserProfile()->getFirstname(),
                        "confirmEmailLink" => $this->generateUrl("valid_mail", [
                            'token' => $user->getToken(),
                        ],
                            UrlGeneratorInterface::ABSOLUTE_URL),
                    ]),
                    'text/html'
                );

            if (!$mailer->send($email)) {
                $this->addFlash("notice", "Merci, nous avons bien enregistré votre inscription. Si vous n'avez pas reçu de mail de confirmation, veuillez contacter l'un de nos administrateur.");
                return $this->redirectToRoute('add_associate');

            }
            $this->addFlash("notice", "Votre inscription a bien été prise en compte. Un e-mail vous a été envoyé pour valider votre inscription.");
            return $this->redirectToRoute('validation_associate');
        }
        return $this->render(
            '@App/Pages/User/add_associate_user.html.twig',
            array('form_associate' => $form->createView(),
            ));
    }

    /**
     * @return Response|\Symfony\Component\HttpFoundation\Response
     * Lists all User entities.
     * @Security("has_role ('ROLE_PROJECT_RESP')")
     * @Route("/", name="validation_associate")
     */
    public function showAssociate($page = 1)
    {
        $maxUsers = 10;
        $company = $this->getUser()->getCompany();
        $em = $this->getDoctrine()->getManager();

        $userProfile = $em->getRepository('AppBundle:User')->getList($page, $maxUsers, $company);
        $users_count = count($userProfile);
        $pagination = [
            'page' => $page,
            'route' => 'validation_associate',
            'pages_count' => ceil(ceil($users_count) / $maxUsers),
            'route_params' => [],
        ];

        return $this->render('@App/Pages/User/show_associate.html.twig', array(
            'User' => $userProfile,
            'pagination' => $pagination,
        ));
    }

    /**
     * Displays a form to edit an existing civil entity.
     *
     * @Route("/edit/{id}", name="associate_edit")
     * @param Request $request
     * @param User $user
     * @return Response|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAssociate(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\ModifyAssociateType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $profile = $this->getUser();
            $userId = $profile->getId();
            $company = $profile->getCompany();
            $repo = $this->getDoctrine()->getRepository('AppBundle:User')->findBy([
                'company' => $company,
            ]);
            $result = [];

            for ($i = 0; $i < count($repo); $i++) {
                $role = $repo[$i]->getRoles();
                if ($role[0] === "ROLE_PROJECT_RESP" && $repo[$i]->getId() != $userId) {
                    $result[] = $repo[$i]->getId();
                }
            }

            $capacity = $user->getRoles();
            if(count($result) == 2 && ($capacity[0] === "ROLE_PROJECT_RESP")){
                $this->addFlash("notice", "Validation impossible. Vous pouvez créer deux comptes responsable projet par société.");
                return $this->redirectToRoute('validation_associate');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('validation_associate', array('id' => $user->getId()));
        }

        return $this->render('@App/Pages/User/modify_associate.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * @param User $user
     * @Security("has_role ('ROLE_PROJECT_RESP')")
     * @Route("/associate_validation/activate/{id}", name="activate_associate")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAssociate(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $user->getIsActiveByAdmin();
        if($status === false) {
            $user->setIsActiveByAdmin(true);
        } else {
            $user->setIsActiveByAdmin(false);
        }
        $em->flush();
        return $this->redirectToRoute('validation_associate');
    }

    /**
     * @param User $user
     * @Route("/delete/{id}", name="delete_associate")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role ('ROLE_PROJECT_RESP')")
     */
    public function deleteAssociate(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('validation_associate');
    }

}

