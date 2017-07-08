<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ForgetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Attachment;
use Swift_Mailer;
use Swift_MailTransport;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('dashboard');
        }

        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('AppBundle:Security:login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
    * @param Request $request
    * @return Response
    * @Route("/forgotten", name="forgotten")
    */
    public function forgottenAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $newUser
             */
            $newUser = $em->getRepository('AppBundle:User')->loadUserByUsername($user->getEmail());
            if (null == $newUser) {
                $this->addFlash("notice", "Nous n'avons pas trouvé cet utilisateur.");
            } else {
                $newUser->setToken($newUser->generateToken());
                $dueDate = new \DateTime("now");
                $dueDate->add(new  \DateInterval("P1D"));
                $newUser->setTokenLimitDate($dueDate);
                $em->persist($newUser);
                $em->flush();

                $mailer = \Swift_Mailer::newInstance($this->get('mailer')->getTransport());

                $email = \Swift_Message::newInstance()
                    ->setSubject('CommunIt : Réinitialisation du mot de passe')
                    ->setFrom($this->getParameter('mail_sender_address'))
                    ->setTo($newUser->getEmail())
                    ->setBody(
                        $this->renderView('AppBundle:Email:forgetPassword.html.twig', [
                            'firstName' => $newUser->getUserProfile()->getFirstname(),
                            'lastName' => $newUser->getUserProfile()->getLastname(),
                            'resetPasswordLink' => $this->generateUrl("reset", [
                                'token' => $newUser->getToken(),
                            ],
                                UrlGeneratorInterface::ABSOLUTE_URL),
                        ]),
                        'text/html'
                    );

                if (!$mailer->send($email)) {
                    $this->addFlash("notice", " Nous sommes désolés, mais le service est actuellement indisponible. Merci de réessayer ultérieurement. Un mail a été envoyé au service technique afin de corriger le problème au plus vite. ");
                    return $this->redirectToRoute('forgotten');
                }
                $this->addFlash("notice", "Un e-mail vous a été envoyé.");
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('AppBundle:Security:passwordProcess.html.twig', [
            'form' => $form->createView(),
            'email' => $user->getEmail(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     * @return RedirectResponse|Response
     * @Route("/reset/{token}", name="reset")
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy([
            "token" => $token
        ]);
        $today = new \DateTime('now');

        if (null !== $user && $user->getTokenLimitDate() > $today) {
            $user->setPassword("");
            $form = $this->createForm(ChangePasswordType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
                $user->setTokenLimitDate(null);
                $user->setToken(null);
                $em->flush();
                $this->addFlash("notice", "Votre mot de passe à bien été changé. Vous pouvez vous desormais connecter.");

                return $this->redirectToRoute('login');
            }
            return $this->render('AppBundle:Security:passwordReset.html.twig', [
                'form' => $form->createView(),
            ]);

        } else {
            $this->addFlash("notice", "Cette demande de réinitialisation de mot de passe n'est pas valide.");
            return $this->redirectToRoute('forgotten');
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/change", name="change_password")
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $row = $request->request-> get('app_bundle_change_password_type')['oldPassword'];
            $encoder = $this->get('security.password_encoder');

            if ($encoder->isPasswordValid($user, $row)) {
                $plainPassword = $user->getPlainPassword();
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', "Le mot de passe est changé ! ");

                return $this->redirectToRoute('dashboard');

            } else {
                $this->addFlash('notice', "Le mot de passe actuel n'est pas correct !");
            }
        }

        return $this->render('AppBundle:Security:passwordChange.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
