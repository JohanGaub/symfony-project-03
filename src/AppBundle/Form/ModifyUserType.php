<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,  ['label' => 'email'])
            ->add('company', CompanyType::class, array(
                'label' => false,
            ))
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Commercial' => 'ROLE_COMMERCIAL',
                    'Technicien' => 'ROLE_TECHNICIAN',
                    'Commercial et Technicien' => ('ROLE_TECHNICIAN' && 'ROLE_COMMERCIAL'),
                    'Chef de Projet' => ('ROLE_PROJECT_RESP'),
                    'Administrateur' => ('ROLE_ADMIN'),
                ]
            ])
            ->add('userProfile', User_profileType::class, array(
                'label' => false,
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }
}