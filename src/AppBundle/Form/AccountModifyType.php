<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 16/07/17
 * Time: 18:02
 */

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AccountModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            /* if ($this->get('security.context')->isGranted('ROLE_PROJECT_RESP')){ {
               $builder
            ->add('company', CompanyType::class, array(
                     'label' => false,
                 ));
                    }*/
            $builder
            ->add('email', EmailType::class,  ['label' => 'Email'])
            ->add('userProfile', User_profileType::class, array(
                'label' => false,
            ))
                ->add('company', CompanyType::class, array(
                    'label' => false,
                ))

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

}