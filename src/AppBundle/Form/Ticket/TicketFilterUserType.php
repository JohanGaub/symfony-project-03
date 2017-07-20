<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\TicketFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TicketFilterAdminType
 * @package AppBundle\Form\Ticket
 */
class TicketFilterUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'label' => "N° de ticket",
                'required' => false,
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'required' => false,
            ])
            ->add('emergency', ChoiceType::class, [
                'label' => 'Urgence',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => [
                    'Normale'   => 'Normale',
                    'Haute'     => 'Haute',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet du ticket',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => [
                    'En attente'   => 'En attente',
                    'En cours'     => 'En cours',
                ],
            ])
            ->add('creationDate', DateType::class, [
                'label'     => 'Date de création',
                'widget'    => 'single_text',
                'required' => false,
                'html5' => false,
                'attr'      => [
                    'placeholder'   => 'jj/mm/aaaa',
                    'format'        => 'dd/MM/yyyy',
                    'class'         => 'js-datepicker',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label'     => 'Date de clôture',
                'widget'    => 'single_text',
                'required'  => false,
                'html5' => false,
                'attr'      => [
                    'placeholder'   => 'jj/mm/aaaa',
                    'format'        => 'dd/MM/yyyy',
                    'class'         => 'js-datepicker',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr'  => [
                    'class'         => 'btn btn-model-small',
                    'aria-hidden'   => 'true',
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setdefaults([
            'data_class'        => TicketFilter::class,
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
