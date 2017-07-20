<?php
namespace AppBundle\Form;

use AppBundle\Entity\UserFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 24/05/17
 * Time: 16:04
 */
class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'email',
                'required' => false
            ))
            ->add('isActiveByAdmin', ChoiceType::class, array (
                'label' => 'Statut',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Actif' => '1',
                    'Inactif' => '0'
                ],
            ))
            ->add('name', TextType::class, array(
                'label' => 'Entreprise',
                'required' => false,
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom',
                'required' => false,
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Nom',
                'required' => false,
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserFilter::class,
            'validation_groups' => false,
            'csrf_protection'   => false,
        ));
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_filter_type';
    }
}