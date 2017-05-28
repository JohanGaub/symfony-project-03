<?php

namespace AppBundle\Form;

use AppBundle\Entity\TechnicalEvolution;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TechnicalEvolutionType
 * @package AppBundle\Form
 */
class TechnicalEvolutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom'])
            ->add('category')
            ->add('product')
            ->add('sum_up', TextareaType::class, ['label' => 'Résumé'])
            ->add('content', TextareaType::class, ['label' => 'Contenu'])
            ->add('reason', TextType::class, ['label' => 'Raison'])
            ->add('status', ChoiceType::class, ['label' => 'Status',
                'choices' => [
                    'Proposé'   => 'Proposé',
                    'En cours'  => 'En cours',
                    'En attente'=> 'En attente',
                    'Terminé'   => 'Terminé',
                ],
            ])
            ->add('origin', TextType::class, ['label' => 'Origine'])
            ->add('expected_delay', DateType::class, ['label' => 'Délais souhaité']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TechnicalEvolution::class,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_technicalEvolution_type';
    }

}