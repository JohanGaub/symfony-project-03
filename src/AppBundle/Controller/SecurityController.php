<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 24/05/17
 * Time: 15:56
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Form\UserType;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class SecurityController extends Controller
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
            $company->setPhone($form['company']['phone']->getData());
            $company->setSiret($form['company']['siret']->getData());
            $company->setEmail($form['company']['email']->getData());

            $userProfile->setFirstname($form['userProfile']['firstname']->getData());
            $userProfile->setLastname($form['userProfile']['lastname']->getData());
            $userProfile->setPhone($form['userProfile']['phone']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('home');

        }

        return $this->render(
            '@App/Pages/register.html.twig',
            array('form' => $form->createView(),
            ));
    }
}