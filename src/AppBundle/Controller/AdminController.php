<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 07/06/17
 * Time: 13:48
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Route("/register_validation", name="admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return RedirectResponse|Response
     * @internal param UserPasswordEncoderInterface $passwordEncoder
     */
    public function RegisterAction(Request $request)
    {
        $user = new User();
        $company = new Company();
        $userProfile = new UserProfile();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_PROJECT_RESP']);
            $user->setIsActive(false);
            $user->setIsActiveByAdmin(false);
            $user->setToken($user->generateToken());
            $dueDate = new \DateTime("now");
            $dueDate->add(new  \DateInterval("P1D"));
            $user->setTokenLimitDate($dueDate);

            $company->setSiret($form['company']['siret']->getData());
            $company->setName($form['company']['name']->getData());
            $company->setAddress($form['company']['address']->getData());
            $company->setTown($form['company']['town']->getData());
            $company->setPostCode($form['company']['postCode']->getData());
            $company->setPhone($form['company']['phone']->getData());
            $company->setEmail($form['company']['email']->getData());

            $userProfile->setFirstname($form['userProfile']['firstname']->getData());
            $userProfile->setLastname($form['userProfile']['lastname']->getData());
            $userProfile->setPhone($form['userProfile']['phone']->getData());

            $siretUser = ($form['company']['siret']->getData());


            $siret = $this->getDoctrine()->getRepository('AppBundle:Company')->findBy(
                array('siret' => $siretUser)
            );

            if (count($siret) >= 1) {
                $this->addFlash("notice", "Votre numéro siret a déjà été enregistré sur notre plateforme. Seulement deux comptes sont autorisés par société. Pour plus d'informations, veuillez contacter notre société.");
                return $this->redirectToRoute('user_registration');
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
                $this->addFlash("notice", "Nous sommes désolés, mais le service est actuellement indisponible. Merci de réessayer ultérieurement. Un mail a été envoyé au service technique afin de corriger le problème au plus vite.");

                return $this->redirectToRoute('user_registration');

            }

            $this->addFlash("notice", "Votre inscription a bien été prise en compte. Un e-mail vous a été envoyé pour valider votre inscription.");

            return $this->redirectToRoute('home');

        }
        return $this->render(
            '@App/Pages/Admin/register.html.twig',
            array('form' => $form->createView(),
            ));
    }

    /**
     * @return Response
     * @Route("/liste/{page}", name="validation_register", )
     * @Security("has_role ('ROLE_ADMIN')")
     */
    public function showRegister($page = 1)
    {

        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $maxUsers = 10;
        $users_count = $repo->countUserTotal();
        $users = $repo->getListing($page, $maxUsers);

        $pagination = [
            'page' => $page,
            'route' => 'validation_register',
            'pages_count' => ceil(ceil($users_count) / $maxUsers),
            'route_params' => [],
        ];

        return $this->render('@App/Pages/Admin/validationRegister.html.twig', array(
            'User' => $users,
            'pagination' => $pagination,
        ));
    }

    /**
     * Displays a form to edit an existing civil entity.
     *
     * @Route("register_validation/{id}/edit", name="user_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editShow(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\ModifyUserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('validation_register', array('id' => $user->getId()));
        }

        return $this->render('@App/Pages/Admin/modify_register.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * @param User $user
     * @Route("/register_validation/delete/{id}", name="delete_register")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('validation_register');
    }

    /**
     * @param User $user
     * @Security("has_role ('ROLE_ADMIN')")
     * @Route("/register_validation/activate/{id}", name="activate_register")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateRegister(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $user->getIsActiveByAdmin();
        if ($status === false) {
            $user->setIsActiveByAdmin(true);
        } else {
            $user->setIsActiveByAdmin(false);
        }
        $em->flush();
        return $this->redirectToRoute('validation_register');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @param User $token
     * @Route("/valid_email/{token}", name="valid_mail")
     */
    public function ValidateEmail(Request $request, $token)
    {
        $time = new \DateTime();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(["token" => $token]);

        if ($user !== null && $user->getTokenLimitDate() > $time) {
            $user->setIsActive(true);
            $user->setToken(null);
            $user->setTokenLimitDate(null);
            $em->persist($user);
            $em->flush();
            $this->addFlash("notice", "Merci d'avoir confirmé votre mail. Un administrateur va valider votre inscription.");
        } else {
            $this->addFlash("notice", "Désolé, nous n'avons pas pu traiter votre demande !");
        }
        return $this->redirectToRoute('home');

    }

}