<?php

namespace AppBundle\Form\User;

use AppBundle\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User_profileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom',
                'required' => false])
            ->add('lastname', TextType::class, ['label' => 'Nom',
                'required' => false])
            ->add('phone', TextType::class, ['label' => 'Mobile',
                'required' => false]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserProfile::class,
            'validation_groups' => false,
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_filter_type';
    }
}