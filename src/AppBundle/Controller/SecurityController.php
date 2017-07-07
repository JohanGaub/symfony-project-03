<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 24/05/17
 * Time: 15:56
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ForgetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @return Response
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
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
     * @Route("/forgotten", name="forgotten")
     * @return Response
     */
    public function forgottenAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgetPasswordType::class, $user);
        $form->handleRequest($request);
        $message = "";
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $newUser
             */
            $newUser = $em->getRepository('AppBundle:User')->findOneBy([
                'email' => $user->getEmail()
            ]);
            if (null == $newUser) {
                $message = "Nous n'avons pas trouvé cet utilisateur";
            } else {
                $newUser->setToken($newUser->generateToken());
                $dueDate = new \DateTime("now");
                $dueDate->add(new  \DateInterval("P1D"));
                $newUser->setTokenLimitDate($dueDate);
                $em->persist($newUser);
                $em->flush();

                $email = \Swift_Message::newInstance()
                    ->setSubject('CommunIt : Réinitialisation du mot de passe')
                    ->setFrom('f.letellier0@gmail.com')
                    ->setTo($newUser->getEmail())
                    ->setBody(
                        $this->renderView('forgetPassword.html.twig', [
                            'name' => $newUser->getUserProfile()->getFirstname(),
                            'resetPasswordLink' => $this->generateUrl("reset", [
                                'token' => $newUser->getToken(),
                            ],

                                UrlGeneratorInterface::ABSOLUTE_URL),
                        ]),
                        'text/html'
                    );
                $this->get('mailer')->send($email);
                $this->addFlash("notice", "Un e-mail vous a été envoyé.");

                return $this->redirectToRoute('login');
            }
        }
        return $this->render('AppBundle:Security:passwordProcess.html.twig', [
            'form' => $form->createView(),
            'email' => $user->getEmail(),
            'message' => $message
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     * @Route("/reset/{token}", name="reset")
     * @return RedirectResponse|Response
     */
    public function resetPasswordAction(Request $request, $token)
    {

        $message = "";
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
                $password = $user->getPassword();
                $verificationPassword = $request->request->get('app_bundle_change_password_type')['passwordCompare'];
                if ($password == $verificationPassword) {
                    $encoder = $this->get('security.password_encoder');
                    $encoded = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($encoded);
                    $user->setTokenLimitDate(null);
                    $user->setToken(null);
                    $em->flush();
                    $this->addFlash("notice", "Votre mot de passe à bien été changé. Vous pouvez vous desormais connecter.");
                    return $this->redirectToRoute('login');
                } else {
                    $message = "Les mots des passe ne correspondent pas.";
                }
            }
            return $this->render('AppBundle:Security:passwordReset.html.twig', [
                'form' => $form->createView(),
                'message' => $message,

            ]);
        } else {
            $this->addFlash("notice", "Cette demande de réinitialisation de mot de passe n'est pas valide.");
            return $this->redirectToRoute('login');
        }
    }

}
