<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Entity\TechnicalEvolutionFilter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TechnicalEvolutionFilterType
 * @package AppBundle\Form\Evolution
 */
class TechnicalEvolutionFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'     => 'Titre',
                'mapped'    => false,
                'required'  => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer mon commentaire'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TechnicalEvolutionFilter::class,
            'validation_groups' => false,
            'csrf_protection'   => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_filter_type';
    }
}