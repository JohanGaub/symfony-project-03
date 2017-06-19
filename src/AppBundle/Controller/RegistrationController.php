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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return RedirectResponse|Response
     * @internal param UserPasswordEncoderInterface $passwordEncoder
     */
    public function RegisterAction(Request $request){
        $user  = new User();
        $company = new Company();
        $userProfile = new UserProfile();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_PROJECT_RESP']);
            $user->setIsActive(0);
            $user->setToken($user->generateToken());
            $dueDate = new \DateTime("now");
            $dueDate->add(new  \DateInterval("P1D"));
            $user->setTokenLimitDate($dueDate);

            $company->setName($form['company']['name']->getData());
            $company->setAddress($form['company']['address']->getData());
            $company->setTown($form['company']['town']->getData());
            $company->setPostCode($form['company']['postCode']->getData());
            $company->setPhone($form['company']['phone']->getData());
            $company->setSiret($form['company']['siret']->getData());
            $company->setEmail($form['company']['email']->getData());

            $userProfile->setFirstname($form['userProfile']['firstname']->getData());
            $userProfile->setLastname($form['userProfile']['lastname']->getData());
            $userProfile->setPhone($form['userProfile']['phone']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mailer = \Swift_Mailer::newInstance($this->get('mailer')->getTransport());

            $email = \Swift_Message::newInstance()
                    ->setSubject('CommunIt : Confirmation de inscription')
                    ->setFrom('f.letellier0@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('@App/Email/confirm.html.twig', [
                            'name' => $user->getUserProfile()->getFirstname(),
                            "confirmEmailLink" => $this->generateUrl("valid_mail",[
                            'token' => $user->getToken(),
                            ],
                            UrlGeneratorInterface::ABSOLUTE_URL),
                            ]),
                            'text/html'
                    );

            if(!$mailer->send($email)) {
                $this->addFlash("notice", "Service indisponible, veuillez réessayer ultérieurement");
                return $this->redirectToRoute('user_registration');

            }



            $this->addFlash("notice", "Votre préinscription a été prise en compte,un e-mail vous a été envoyé.");

                return $this->redirectToRoute('home');
        }
        return $this->render(
            '@App/Pages/register.html.twig',
            array('form' => $form->createView(),
            ));
    }

    /**
     * @return Response
     * Lists all User entities.
     *
     * @Route("/register_validation", name="validation_register")
     */
    public function showRegister(){

        $em = $this->getDoctrine()->getManager();
        $userProfile = $em->getRepository('AppBundle:User')->findBy(
            array('isActive' => '0')
        );

        return $this->render('@App/Pages/ValidationRegister.html.twig', array(
            'User' => $userProfile,
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
     * @Route("/register_validation/activate/{id}", name="activate_register")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setIsActive(1);
        $em->flush();
        $em->persist($user);
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

        if($user !== null && $user->getTokenLimitDate() > $time){
            $user->setIsActive(true);
            $user->setToken(null);
            $user->setTokenLimitDate(null);

            $em->persist($user);
            $em->flush();
            $this->addFlash("notice", "Votre avez confirmé votre mail, un administrateur va valider votre inscription !");
        } else {
            $this->addFlash("notice", "Votre token d'inscription a expiré !");
        }
        dump($user);
        return $this->redirectToRoute('home');

    }

}