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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
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

            $company->setName($form['company']['name']->getData());
            $company->setAdress($form['company']['adress']->getData());
            $company->setTown($form['company']['town']->getData());
            $company->setPhone2($form['company']['phone2']->getData());
            $company->setSiret($form['company']['siret']->getData());
            $company->setEmail($form['company']['email']->getData());

            $userProfile->setFirstname($form['userProfile']['firstname']->getData());
            $userProfile->setLastname($form['userProfile']['lastname']->getData());
            $userProfile->setPhone1($form['userProfile']['phone1']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
   $email = \Swift_Message::newInstance()
                    ->setSubject('CommunIt : Confirmation de inscription')
                    ->setFrom('f.letellier0@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('@App/Email/confirm.html.twig', [
                            'name' => $user->getUserProfile()->getFirstname(),
                        ]),
                            'text/html'
                    );
                $this->get('mailer')->send($email);
                $this->addFlash("notice", "Votre pré-inscription a été prise en compte,un e-mail vous a été envoyé.");

                return $this->redirectToRoute('home');
        }

        return $this->render(
            '@App/Pages/register.html.twig',
            array('form' => $form->createView(),
            ));
    }
}