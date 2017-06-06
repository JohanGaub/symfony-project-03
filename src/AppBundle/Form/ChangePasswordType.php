<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Entrez votre ancien mot de passe',
                'attr' => ['placeholder' => 'Entrez votre ancien mot de passe'],
                'mapped' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Entrez votre nouveau mot de passe',
                'attr' => ['placeholder' => 'Entrez votre nouveau mot de passe']
            ])

            ->add('passwordCompare', PasswordType::class, [
                'label' => 'Entrez à nouveau le mot de passe',
                'attr' => ['placeholder' => 'Entrez à nouveau le mot de passe'],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réinitialiser'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_change_password_type';
    }
}
