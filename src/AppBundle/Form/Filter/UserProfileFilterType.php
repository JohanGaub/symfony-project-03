<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 13/07/17
 * Time: 14:17
 */

namespace AppBundle\Form\Filter;


use AppBundle\Entity\UserProfile;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('phone', TextType::class, ['label' => 'Mobile']);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserProfile::class,
        ));
    }
}